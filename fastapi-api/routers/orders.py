from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session, selectinload
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from database import get_db
from models.models import Order, OrderItem, Autopart, User, CartItem
from schemas.schemas import OrderOut, OrderCreate, OrderStatus
from .auth import get_current_user

router = APIRouter(prefix="/orders", tags=["orders"])

@router.patch("/{order_id}/status", response_model=OrderOut)
def update_order_status(order_id: int, status: OrderStatus, db: Session = Depends(get_db), current_user: User = Depends(get_current_user)):
    if current_user.role not in ["admin", "employee"]:
        raise HTTPException(status_code=403, detail="No tienes permisos")
    
    order = db.query(Order).filter(Order.id == order_id).first()
    if not order:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")
    
    order.status = status
    db.commit()
    order = (
        db.query(Order)
        .options(selectinload(Order.items).selectinload(OrderItem.autopart))
        .filter(Order.id == order_id)
        .first()
    )
    return order

@router.post("/", response_model=OrderOut)
def create_order(order_in: OrderCreate, db: Session = Depends(get_db), current_user: User = Depends(get_current_user)):
    # Crear el pedido
    new_order = Order(user_id=current_user.id, total_price=0.0)
    db.add(new_order)
    db.flush() # Para obtener el ID del pedido
    
    total_price = 0.0
    for item in order_in.items:
        autopart = db.query(Autopart).filter(Autopart.id == item.autopart_id).first()
        if not autopart:
            raise HTTPException(status_code=404, detail=f"Autoparte con ID {item.autopart_id} no encontrada")
        
        if autopart.stock < item.quantity:
            raise HTTPException(status_code=400, detail=f"Stock insuficiente para {autopart.name}")
        
        # Actualizar stock
        autopart.stock -= item.quantity
        
        # Crear item del pedido
        order_item = OrderItem(
            order_id=new_order.id,
            autopart_id=autopart.id,
            quantity=item.quantity,
            unit_price=autopart.price
        )
        total_price += autopart.price * item.quantity
        db.add(order_item)

    db.query(CartItem).filter(CartItem.user_id == current_user.id).delete(synchronize_session=False)

    new_order.total_price = total_price
    db.commit()
    order = (
        db.query(Order)
        .options(selectinload(Order.items).selectinload(OrderItem.autopart))
        .filter(Order.id == new_order.id)
        .first()
    )
    return order

@router.get("/", response_model=list[OrderOut])
def get_user_orders(db: Session = Depends(get_db), current_user: User = Depends(get_current_user)):
    # Si es admin, puede ver todos? El requerimiento dice "todos los pedidos del usuarios"
    # Por ahora, solo los del usuario actual
    return db.query(Order).filter(Order.user_id == current_user.id).all()

@router.get("/all", response_model=list[OrderOut])
def get_all_orders(db: Session = Depends(get_db), current_user: User = Depends(get_current_user)):
    # Solo admin puede ver todos
    if current_user.role != "admin":
        raise HTTPException(status_code=403, detail="No tienes permisos")
    return db.query(Order).all()

@router.get("/{order_id}", response_model=OrderOut)
def get_order(order_id: int, db: Session = Depends(get_db), current_user: User = Depends(get_current_user)):
    order = (
        db.query(Order)
        .options(selectinload(Order.items).selectinload(OrderItem.autopart))
        .filter(Order.id == order_id)
        .first()
    )
    if not order:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    if order.user_id != current_user.id and current_user.role != "admin":
        raise HTTPException(status_code=403, detail="No tienes permisos")

    return order
