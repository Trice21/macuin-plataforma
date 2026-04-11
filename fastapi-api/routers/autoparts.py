from typing import Optional

from fastapi import APIRouter, Depends, HTTPException, Query, status
from sqlalchemy import or_
from sqlalchemy.orm import Session
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from database import get_db
from models.models import Autopart, User
from schemas.schemas import AutopartOut, AutopartCreate, AutopartUpdate
from .auth import get_admin_user

router = APIRouter(prefix="/autoparts", tags=["autoparts"])

@router.get("/", response_model=list[AutopartOut])
def get_autoparts(
    db: Session = Depends(get_db),
    q: Optional[str] = Query(None, description="Buscar en nombre o descripción"),
    category: Optional[str] = Query(None),
):
    query = db.query(Autopart)
    if q:
        like = f"%{q}%"
        query = query.filter(
            or_(Autopart.name.ilike(like), Autopart.description.ilike(like))
        )
    if category:
        query = query.filter(Autopart.category == category)
    return query.all()

@router.get("/{autopart_id}", response_model=AutopartOut)
def get_autopart(autopart_id: int, db: Session = Depends(get_db)):
    autopart = db.query(Autopart).filter(Autopart.id == autopart_id).first()
    if not autopart:
        raise HTTPException(status_code=404, detail="Autoparte no encontrada")
    return autopart

@router.post("/", response_model=AutopartOut)
def create_autopart(autopart_in: AutopartCreate, db: Session = Depends(get_db), current_user: User = Depends(get_admin_user)):
    new_autopart = Autopart(**autopart_in.dict())
    db.add(new_autopart)
    db.commit()
    db.refresh(new_autopart)
    return new_autopart

@router.put("/{autopart_id}", response_model=AutopartOut)
def update_autopart(autopart_id: int, autopart_in: AutopartUpdate, db: Session = Depends(get_db), current_user: User = Depends(get_admin_user)):
    autopart = db.query(Autopart).filter(Autopart.id == autopart_id).first()
    if not autopart:
        raise HTTPException(status_code=404, detail="Autoparte no encontrada")
    
    for key, value in autopart_in.dict(exclude_unset=True).items():
        setattr(autopart, key, value)
        
    db.commit()
    db.refresh(autopart)
    return autopart

@router.delete("/{autopart_id}")
def delete_autopart(autopart_id: int, db: Session = Depends(get_db), current_user: User = Depends(get_admin_user)):
    autopart = db.query(Autopart).filter(Autopart.id == autopart_id).first()
    if not autopart:
        raise HTTPException(status_code=404, detail="Autoparte no encontrada")
    
    db.delete(autopart)
    db.commit()
    return {"message": "Autoparte eliminada exitosamente"}
