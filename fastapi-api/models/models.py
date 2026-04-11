from sqlalchemy import Column, Integer, String, Float, ForeignKey, DateTime, Enum, Text
from sqlalchemy.dialects.mysql import BIGINT
from sqlalchemy.orm import relationship
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from database import Base
from datetime import datetime
import enum
class UserRole(str, enum.Enum):
    ADMIN = "admin"
    EMPLOYEE = "employee"
    CLIENT = "client"

class User(Base):
    __tablename__ = "users"

    id = Column(BIGINT(unsigned=True), primary_key=True, index=True)
    name = Column(String(100), nullable=False)
    email = Column(String(100), unique=True, index=True, nullable=False)
    password = Column(String(255), nullable=False)
    role = Column(Enum(UserRole), default=UserRole.CLIENT)
    created_at = Column(DateTime, default=datetime.utcnow)

    orders = relationship("Order", back_populates="user")
    cart_items = relationship("CartItem", back_populates="user")

class Autopart(Base):
    __tablename__ = "autoparts"

    id = Column(BIGINT(unsigned=True), primary_key=True, index=True)
    name = Column(String(100), nullable=False)
    description = Column(Text, nullable=True)
    price = Column(Float, nullable=False)
    stock = Column(Integer, default=0)
    category = Column(String(50), nullable=True)
    image_url = Column(String(255), nullable=True)
    created_at = Column(DateTime, default=datetime.utcnow)

    order_items = relationship("OrderItem", back_populates="autopart")
    cart_items = relationship("CartItem", back_populates="autopart")

class OrderStatus(str, enum.Enum):
    PENDING = "pending"
    PROCESSING = "processing"
    SHIPPED = "shipped"
    DELIVERED = "delivered"
    CANCELLED = "cancelled"

class Order(Base):
    __tablename__ = "orders"

    id = Column(BIGINT(unsigned=True), primary_key=True, index=True)
    user_id = Column(BIGINT(unsigned=True), ForeignKey("users.id"))
    status = Column(Enum(OrderStatus), default=OrderStatus.PENDING)
    total_price = Column(Float, default=0.0)
    created_at = Column(DateTime, default=datetime.utcnow)

    user = relationship("User", back_populates="orders")
    items = relationship("OrderItem", back_populates="order")

class OrderItem(Base):
    __tablename__ = "order_items"

    id = Column(BIGINT(unsigned=True), primary_key=True, index=True)
    order_id = Column(BIGINT(unsigned=True), ForeignKey("orders.id"))
    autopart_id = Column(BIGINT(unsigned=True), ForeignKey("autoparts.id"))
    quantity = Column(Integer, nullable=False)
    unit_price = Column(Float, nullable=False)

    order = relationship("Order", back_populates="items")
    autopart = relationship("Autopart", back_populates="order_items")


class CartItem(Base):
    __tablename__ = "cart_items"

    id = Column(BIGINT(unsigned=True), primary_key=True, index=True)
    user_id = Column(BIGINT(unsigned=True), ForeignKey("users.id"))
    autopart_id = Column(BIGINT(unsigned=True), ForeignKey("autoparts.id"))
    quantity = Column(Integer, nullable=False, default=1)
    created_at = Column(DateTime, nullable=True)
    updated_at = Column(DateTime, nullable=True)

    user = relationship("User", back_populates="cart_items")
    autopart = relationship("Autopart", back_populates="cart_items")
