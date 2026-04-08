from pydantic import BaseModel, EmailStr, Field
from typing import Optional, List
from datetime import datetime
from enum import Enum

# Enum para Roles
class UserRole(str, Enum):
    ADMIN = "admin"
    EMPLOYEE = "employee"
    CLIENT = "client"

# Enum para Estados de Pedido
class OrderStatus(str, Enum):
    PENDING = "pending"
    PROCESSING = "processing"
    SHIPPED = "shipped"
    DELIVERED = "delivered"
    CANCELLED = "cancelled"

# --- User Schemas ---
class UserBase(BaseModel):
    name: str
    email: EmailStr

class UserCreate(UserBase):
    password: str
    role: Optional[UserRole] = UserRole.CLIENT

class UserUpdate(BaseModel):
    name: Optional[str] = None
    email: Optional[EmailStr] = None
    role: Optional[UserRole] = None

class UserOut(UserBase):
    id: int
    role: UserRole
    created_at: datetime

    class Config:
        from_attributes = True

# --- Autopart Schemas ---
class AutopartBase(BaseModel):
    name: str
    description: Optional[str] = None
    price: float
    stock: int
    category: Optional[str] = None
    image_url: Optional[str] = None

class AutopartCreate(AutopartBase):
    pass

class AutopartUpdate(BaseModel):
    name: Optional[str] = None
    description: Optional[str] = None
    price: Optional[float] = None
    stock: Optional[int] = None
    category: Optional[str] = None
    image_url: Optional[str] = None

class AutopartOut(AutopartBase):
    id: int
    created_at: datetime

    class Config:
        from_attributes = True

# --- Order Item Schemas ---
class OrderItemBase(BaseModel):
    autopart_id: int
    quantity: int

class OrderItemCreate(OrderItemBase):
    pass

class OrderItemOut(OrderItemBase):
    id: int
    unit_price: float

    class Config:
        from_attributes = True

# --- Order Schemas ---
class OrderBase(BaseModel):
    pass

class OrderCreate(OrderBase):
    items: List[OrderItemCreate]

class OrderOut(OrderBase):
    id: int
    user_id: int
    status: OrderStatus
    total_price: float
    created_at: datetime
    items: List[OrderItemOut]

    class Config:
        from_attributes = True

# --- Auth Schemas ---
class Token(BaseModel):
    access_token: str
    token_type: str

class TokenData(BaseModel):
    email: Optional[str] = None
    role: Optional[str] = None
