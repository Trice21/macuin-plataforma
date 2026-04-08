from fastapi import APIRouter, Depends, HTTPException, status, Query
from fastapi.responses import FileResponse
from sqlalchemy.orm import Session
from sqlalchemy import func, and_, or_, desc, asc
import pandas as pd
from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
from docx import Document
import sys
import os
import io
from datetime import datetime, date
from typing import Optional, List

sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from database import get_db
from models.models import Order, OrderItem, Autopart, User, UserRole
from .auth import get_admin_user

router = APIRouter(prefix="/reports", tags=["reports"])

# Helper para exportar a diferentes formatos
def export_data(df, report_name, format):
    filename = f"{report_name}.{format}"
    filepath = f"temp/{filename}"
    
    if not os.path.exists("temp"):
        os.makedirs("temp")
    
    if format == "xlsx":
        df.to_excel(filepath, index=False)
    elif format == "pdf":
        c = canvas.Canvas(filepath, pagesize=letter)
        width, height = letter
        c.drawString(100, height - 50, f"Reporte: {report_name}")
        y = height - 100
        for i, row in df.iterrows():
            line = " | ".join([str(val) for val in row.values])
            c.drawString(100, y, line)
            y -= 20
            if y < 50:
                c.showPage()
                y = height - 50
        c.save()
    elif format == "docx":
        doc = Document()
        doc.add_heading(report_name, 0)
        table = doc.add_table(rows=1, cols=len(df.columns))
        hdr_cells = table.rows[0].cells
        for i, col in enumerate(df.columns):
            hdr_cells[i].text = str(col)
        for i, row in df.iterrows():
            row_cells = table.add_row().cells
            for j, val in enumerate(row.values):
                row_cells[j].text = str(val)
        doc.save(filepath)
    else:
        raise HTTPException(status_code=400, detail="Formato no soportado")
    
    return filepath

@router.get("/sales-by-autopart")
def report_sales_by_autopart(
    format: str = "xlsx",
    start_date: Optional[str] = Query(None, description="Fecha de inicio (YYYY-MM-DD)"),
    end_date: Optional[str] = Query(None, description="Fecha de fin (YYYY-MM-DD)"),
    category: Optional[str] = Query(None, description="Categoría del producto"),
    min_sales: Optional[int] = Query(None, description="Ventas mínimas"),
    sort_by: Optional[str] = Query("quantity", description="Ordenar por: quantity, revenue, name"),
    order: Optional[str] = Query("desc", description="Orden: asc, desc"),
    db: Session = Depends(get_db), 
    current_user: User = Depends(get_admin_user)
):
    # Construir query base
    query = db.query(
        Autopart.name,
        Autopart.category,
        func.sum(OrderItem.quantity).label("total_sold"),
        func.sum(OrderItem.quantity * OrderItem.unit_price).label("total_revenue")
    ).join(OrderItem).join(Order)
    
    # Aplicar filtros
    if start_date:
        try:
            start_dt = datetime.strptime(start_date, "%Y-%m-%d")
            query = query.filter(Order.created_at >= start_dt)
        except ValueError:
            raise HTTPException(status_code=400, detail="Formato de fecha inválido")
    
    if end_date:
        try:
            end_dt = datetime.strptime(end_date, "%Y-%m-%d")
            query = query.filter(Order.created_at <= end_dt)
        except ValueError:
            raise HTTPException(status_code=400, detail="Formato de fecha inválido")
    
    if category:
        query = query.filter(Autopart.category == category)
    
    # Agrupar y filtrar por ventas mínimas
    query = query.group_by(Autopart.id, Autopart.name, Autopart.category)
    
    # Aplicar ordenamiento
    if sort_by == "quantity":
        order_col = func.sum(OrderItem.quantity)
    elif sort_by == "revenue":
        order_col = func.sum(OrderItem.quantity * OrderItem.unit_price)
    else:  # name
        order_col = Autopart.name
    
    if order == "asc":
        query = query.order_by(asc(order_col))
    else:
        query = query.order_by(desc(order_col))
    
    # Ejecutar query
    results = query.all()
    
    # Filtrar por ventas mínimas si se especificó
    if min_sales is not None:
        results = [r for r in results if r.total_sold >= min_sales]
    
    df = pd.DataFrame(results, columns=["Nombre", "Categoría", "Cantidad Vendida", "Ingresos Totales"])
    filepath = export_data(df, "Ventas_por_Autoparte", format)
    return FileResponse(filepath, filename=os.path.basename(filepath))

@router.get("/top-users")
def report_top_users(
    format: str = "xlsx",
    start_date: Optional[str] = Query(None, description="Fecha de inicio (YYYY-MM-DD)"),
    end_date: Optional[str] = Query(None, description="Fecha de fin (YYYY-MM-DD)"),
    min_orders: Optional[int] = Query(None, description="Pedidos mínimos"),
    min_spent: Optional[float] = Query(None, description="Gasto mínimo"),
    user_role: Optional[str] = Query(None, description="Rol de usuario"),
    limit: Optional[int] = Query(10, description="Límite de resultados"),
    db: Session = Depends(get_db), 
    current_user: User = Depends(get_admin_user)
):
    # Construir query base
    query = db.query(
        User.name,
        User.email,
        User.role,
        func.count(Order.id).label("order_count"),
        func.sum(Order.total_price).label("total_spent")
    ).join(Order)
    
    # Aplicar filtros
    if start_date:
        try:
            start_dt = datetime.strptime(start_date, "%Y-%m-%d")
            query = query.filter(Order.created_at >= start_dt)
        except ValueError:
            raise HTTPException(status_code=400, detail="Formato de fecha inválido")
    
    if end_date:
        try:
            end_dt = datetime.strptime(end_date, "%Y-%m-%d")
            query = query.filter(Order.created_at <= end_dt)
        except ValueError:
            raise HTTPException(status_code=400, detail="Formato de fecha inválido")
    
    if user_role:
        query = query.filter(User.role == user_role)
    
    # Agrupar y ordenar
    query = query.group_by(User.id, User.name, User.email, User.role)
    query = query.order_by(desc(func.count(Order.id)))
    
    # Ejecutar query con límite
    results = query.limit(limit).all()
    
    # Filtrar por pedidos mínimos y gasto mínimo
    if min_orders is not None:
        results = [r for r in results if r.order_count >= min_orders]
    
    if min_spent is not None:
        results = [r for r in results if (r.total_spent or 0) >= min_spent]
    
    df = pd.DataFrame(results, columns=["Nombre", "Email", "Rol", "Total Pedidos", "Total Gastado"])
    filepath = export_data(df, "Mejores_Clientes", format)
    return FileResponse(filepath, filename=os.path.basename(filepath))

@router.get("/orders-status")
def report_orders_status(
    format: str = "xlsx",
    start_date: Optional[str] = Query(None, description="Fecha de inicio (YYYY-MM-DD)"),
    end_date: Optional[str] = Query(None, description="Fecha de fin (YYYY-MM-DD)"),
    status: Optional[List[str]] = Query(None, description="Estados de pedidos"),
    min_amount: Optional[float] = Query(None, description="Monto mínimo"),
    max_amount: Optional[float] = Query(None, description="Monto máximo"),
    group_by: Optional[str] = Query("status", description="Agrupar por: status, month, week, day"),
    db: Session = Depends(get_db), 
    current_user: User = Depends(get_admin_user)
):
    # Construir query base
    query = db.query(Order)
    
    # Aplicar filtros
    if start_date:
        try:
            start_dt = datetime.strptime(start_date, "%Y-%m-%d")
            query = query.filter(Order.created_at >= start_dt)
        except ValueError:
            raise HTTPException(status_code=400, detail="Formato de fecha inválido")
    
    if end_date:
        try:
            end_dt = datetime.strptime(end_date, "%Y-%m-%d")
            query = query.filter(Order.created_at <= end_dt)
        except ValueError:
            raise HTTPException(status_code=400, detail="Formato de fecha inválido")
    
    if status:
        query = query.filter(Order.status.in_(status))
    
    if min_amount is not None:
        query = query.filter(Order.total_price >= min_amount)
    
    if max_amount is not None:
        query = query.filter(Order.total_price <= max_amount)
    
    # Agrupar según el parámetro
    if group_by == "month":
        query = query.with_entities(
            func.date_trunc('month', Order.created_at).label("period"),
            func.count(Order.id).label("count"),
            func.sum(Order.total_price).label("total")
        ).group_by(func.date_trunc('month', Order.created_at))
        results = query.all()
        df = pd.DataFrame(results, columns=["Período", "Cantidad Pedidos", "Monto Total"])
        
    elif group_by == "week":
        query = query.with_entities(
            func.date_trunc('week', Order.created_at).label("period"),
            func.count(Order.id).label("count"),
            func.sum(Order.total_price).label("total")
        ).group_by(func.date_trunc('week', Order.created_at))
        results = query.all()
        df = pd.DataFrame(results, columns=["Período", "Cantidad Pedidos", "Monto Total"])
        
    elif group_by == "day":
        query = query.with_entities(
            func.date(Order.created_at).label("period"),
            func.count(Order.id).label("count"),
            func.sum(Order.total_price).label("total")
        ).group_by(func.date(Order.created_at))
        results = query.all()
        df = pd.DataFrame(results, columns=["Fecha", "Cantidad Pedidos", "Monto Total"])
        
    else:  # status (default)
        query = query.with_entities(
            Order.status,
            func.count(Order.id).label("count"),
            func.sum(Order.total_price).label("total")
        ).group_by(Order.status)
        results = query.all()
        df = pd.DataFrame(results, columns=["Estado", "Cantidad Pedidos", "Monto Total"])
    
    filepath = export_data(df, "Estado_de_Pedidos", format)
    return FileResponse(filepath, filename=os.path.basename(filepath))

@router.get("/inventory")
def report_inventory(
    format: str = "xlsx",
    category: Optional[str] = Query(None, description="Categoría del producto"),
    stock_level: Optional[str] = Query(None, description="Nivel de stock"),
    min_price: Optional[float] = Query(None, description="Precio mínimo"),
    max_price: Optional[float] = Query(None, description="Precio máximo"),
    sort_by: Optional[str] = Query("name", description="Ordenar por"),
    include_out_of_stock: Optional[str] = Query("true", description="Incluir sin stock"),
    db: Session = Depends(get_db), 
    current_user: User = Depends(get_admin_user)
):
    # Construir query base
    query = db.query(
        Autopart.name,
        Autopart.description,
        Autopart.price,
        Autopart.stock,
        Autopart.category,
        Autopart.created_at
    )
    
    # Aplicar filtros
    if category:
        query = query.filter(Autopart.category == category)
    
    # Filtro de nivel de stock
    if stock_level == "low":
        query = query.filter(and_(Autopart.stock > 0, Autopart.stock < 10))
    elif stock_level == "critical":
        query = query.filter(and_(Autopart.stock > 0, Autopart.stock < 5))
    elif stock_level == "out":
        query = query.filter(Autopart.stock == 0)
    elif stock_level == "normal":
        query = query.filter(Autopart.stock >= 10)
    
    # Filtros de precio
    if min_price is not None:
        query = query.filter(Autopart.price >= min_price)
    
    if max_price is not None:
        query = query.filter(Autopart.price <= max_price)
    
    # Excluir sin stock si se especificó
    if include_out_of_stock == "false":
        query = query.filter(Autopart.stock > 0)
    
    # Aplicar ordenamiento
    if sort_by == "stock":
        query = query.order_by(Autopart.stock)
    elif sort_by == "price":
        query = query.order_by(Autopart.price)
    elif sort_by == "category":
        query = query.order_by(Autopart.category, Autopart.name)
    elif sort_by == "created_at":
        query = query.order_by(desc(Autopart.created_at))
    else:  # name (default)
        query = query.order_by(Autopart.name)
    
    # Ejecutar query
    results = query.all()
    
    df = pd.DataFrame(results, columns=[
        "Nombre", "Descripción", "Precio", "Stock", "Categoría", "Fecha Creación"
    ])
    filepath = export_data(df, "Inventario_Completo", format)
    return FileResponse(filepath, filename=os.path.basename(filepath))

@router.get("/low-stock")
def report_low_stock(format: str = "xlsx", db: Session = Depends(get_db), current_user: User = Depends(get_admin_user)):
    data = db.query(
        Autopart.name,
        Autopart.stock,
        Autopart.category
    ).filter(Autopart.stock < 10).all()
    
    df = pd.DataFrame(data, columns=["Nombre", "Stock", "Categoría"])
    filepath = export_data(df, "Stock_Bajo", format)
    return FileResponse(filepath, filename=os.path.basename(filepath))
