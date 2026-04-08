import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from database import SessionLocal, engine, Base
from models.models import User, UserRole, Autopart, Order, OrderItem, OrderStatus
from core.security import get_password_hash
from datetime import datetime

def seed():
    db = SessionLocal()
    # Crear tablas si no existen
    Base.metadata.create_all(bind=engine)
    
    # Crear admin por defecto
    admin = db.query(User).filter(User.email == "admin@macuin.com").first()
    if not admin:
        admin = User(
            name="Administrador",
            email="admin@macuin.com",
            password=get_password_hash("admin123"),
            role=UserRole.ADMIN
        )
        db.add(admin)
        print("Admin creado: admin@macuin.com / admin123")
    
    # Crear cliente por defecto
    client = db.query(User).filter(User.email == "cliente@example.com").first()
    if not client:
        client = User(
            name="Juan Perez",
            email="cliente@example.com",
            password=get_password_hash("cliente123"),
            role=UserRole.CLIENT
        )
        db.add(client)
        db.flush() # Para obtener el ID
        print("Cliente creado: cliente@example.com / cliente123")
    else:
        print("Cliente ya existe")

    # Crear algunas autopartes
    if db.query(Autopart).count() == 0:
        autoparts = [
            Autopart(name="Amortiguador", description="Amortiguador delantero", price=1200.0, stock=50, category="Suspensión"),
            Autopart(name="Bomba de agua", description="Bomba de agua para motor", price=850.0, stock=30, category="Motor"),
            Autopart(name="Bujía", description="Bujía de encendido", price=150.0, stock=200, category="Encendido"),
            Autopart(name="Filtro de aire", description="Filtro de aire de alto flujo", price=350.0, stock=100, category="Motor"),
        ]
        db.add_all(autoparts)
        db.flush()
        print("Autopartes iniciales creadas")
    
    # Crear un pedido para el cliente si no tiene pedidos
    if client and db.query(Order).filter(Order.user_id == client.id).count() == 0:
        # Obtener algunas autopartes para el pedido
        parts = db.query(Autopart).limit(2).all()
        if parts:
            total = sum(p.price * 2 for p in parts)
            new_order = Order(
                user_id=client.id,
                status=OrderStatus.PENDING,
                total_price=total,
                created_at=datetime.utcnow()
            )
            db.add(new_order)
            db.flush()

            for p in parts:
                item = OrderItem(
                    order_id=new_order.id,
                    autopart_id=p.id,
                    quantity=2,
                    unit_price=p.price
                )
                db.add(item)
            
            print(f"Pedido creado para {client.email} con ID {new_order.id}")
    
    db.commit()
    db.close()

if __name__ == "__main__":
    seed()
