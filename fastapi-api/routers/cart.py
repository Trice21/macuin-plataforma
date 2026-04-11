from datetime import datetime

from fastapi import APIRouter, Depends, HTTPException, Response, status
from sqlalchemy.orm import Session, selectinload

import sys
import os

sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from database import get_db
from models.models import CartItem, Autopart, User
from schemas.schemas import CartItemOut, CartItemAdd
from .auth import get_current_user

router = APIRouter(prefix="/cart", tags=["cart"])


@router.get("/", response_model=list[CartItemOut])
def get_cart(
    db: Session = Depends(get_db),
    current_user: User = Depends(get_current_user),
):
    return (
        db.query(CartItem)
        .options(selectinload(CartItem.autopart))
        .filter(CartItem.user_id == current_user.id)
        .all()
    )


@router.post("/items", response_model=CartItemOut)
def add_or_update_item(
    body: CartItemAdd,
    db: Session = Depends(get_db),
    current_user: User = Depends(get_current_user),
):
    autopart = db.query(Autopart).filter(Autopart.id == body.autopart_id).first()
    if not autopart:
        raise HTTPException(status_code=404, detail="Autoparte no encontrada")
    if autopart.stock < body.quantity:
        raise HTTPException(status_code=400, detail="Stock insuficiente")

    existing = (
        db.query(CartItem)
        .filter(
            CartItem.user_id == current_user.id,
            CartItem.autopart_id == body.autopart_id,
        )
        .first()
    )
    now = datetime.utcnow()
    if existing:
        new_qty = existing.quantity + body.quantity
        if autopart.stock < new_qty:
            raise HTTPException(status_code=400, detail="Stock insuficiente")
        existing.quantity = new_qty
        existing.updated_at = now
        row = existing
    else:
        row = CartItem(
            user_id=current_user.id,
            autopart_id=body.autopart_id,
            quantity=body.quantity,
            created_at=now,
            updated_at=now,
        )
        db.add(row)

    db.commit()
    db.refresh(row)
    row = (
        db.query(CartItem)
        .options(selectinload(CartItem.autopart))
        .filter(CartItem.id == row.id)
        .first()
    )
    return row


@router.delete("/items/{item_id}")
def delete_cart_item(
    item_id: int,
    db: Session = Depends(get_db),
    current_user: User = Depends(get_current_user),
):
    row = (
        db.query(CartItem)
        .filter(CartItem.id == item_id, CartItem.user_id == current_user.id)
        .first()
    )
    if not row:
        raise HTTPException(status_code=404, detail="Ítem no encontrado")
    db.delete(row)
    db.commit()
    return Response(status_code=status.HTTP_204_NO_CONTENT)


@router.delete("/")
def clear_cart(
    db: Session = Depends(get_db),
    current_user: User = Depends(get_current_user),
):
    db.query(CartItem).filter(CartItem.user_id == current_user.id).delete()
    db.commit()
    return Response(status_code=status.HTTP_204_NO_CONTENT)
