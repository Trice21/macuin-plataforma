from fastapi import FastAPI, Depends
from fastapi.middleware.cors import CORSMiddleware
import models.models as models
from database import engine, Base, SessionLocal
from routers import auth, users, autoparts, orders, reports, cart
from models.models import User, UserRole, Autopart
from core.security import get_password_hash
import time
from sqlalchemy.exc import OperationalError

app = FastAPI(title="MACUIN API", description="API Central de la Plataforma MACUIN", version="1.0.0")

# Semilla de datos iniciales
def seed_data():
    db = SessionLocal()
    try:
        if db.query(User).filter(User.email == "admin@macuin.com").first() is None:
            admin = User(
                name="Administrador",
                email="admin@macuin.com",
                password=get_password_hash("admin123"),
                role=UserRole.ADMIN
            )
            db.add(admin)
            print("Admin creado: admin@macuin.com / admin123")
        
        if db.query(Autopart).count() == 0:
            autoparts = [
                Autopart(name="Amortiguador", description="Amortiguador delantero", price=1200.0, stock=50, category="Suspensión"),
                Autopart(name="Bomba de agua", description="Bomba de agua para motor", price=850.0, stock=30, category="Motor"),
                Autopart(name="Bujía", description="Bujía de encendido", price=150.0, stock=200, category="Encendido"),
                Autopart(name="Filtro de aire", description="Filtro de aire de alto flujo", price=350.0, stock=100, category="Motor"),
            ]
            db.add_all(autoparts)
            print("Autopartes iniciales creadas")
        
        db.commit()
    finally:
        db.close()

# Inicialización de BD con reintentos
def init_db():
    max_retries = 5
    retry_delay = 5
    for i in range(max_retries):
        try:
            Base.metadata.create_all(bind=engine)
            seed_data()
            print("Base de datos inicializada correctamente")
            break
        except OperationalError as e:
            if i < max_retries - 1:
                print(f"Error conectando a la BD. Reintentando en {retry_delay}s... ({i+1}/{max_retries})")
                time.sleep(retry_delay)
            else:
                print("No se pudo conectar a la base de datos después de varios intentos.")
                raise e

@app.on_event("startup")
async def startup_event():
    init_db()

# Configuración de CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"], # Ajustar en producción
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Incluir routers
app.include_router(auth.router)
app.include_router(users.router)
app.include_router(autoparts.router)
app.include_router(orders.router)
app.include_router(cart.router)
app.include_router(reports.router)

@app.get("/")
def read_root():
    return {"message": "Bienvenido a la API de MACUIN"}
