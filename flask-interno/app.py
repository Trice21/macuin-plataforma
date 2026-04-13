from flask import Flask, render_template, request, redirect, url_for, session, flash, send_from_directory
import requests
import os
import uuid
from werkzeug.utils import secure_filename
from dotenv import load_dotenv

load_dotenv()

app = Flask(__name__)
app.secret_key = os.getenv("SECRET_KEY", "flask_secret_key_123")
API_URL = os.getenv("API_URL", "http://macuin-api:8000")

# Configuración para subida de archivos
UPLOAD_FOLDER = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'static', 'images', 'autoparts')
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif'}
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB max file size

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def save_uploaded_file(file):
    if file and allowed_file(file.filename):
        filename = secure_filename(file.filename)
        # Generar nombre único para evitar conflictos
        unique_filename = f"{uuid.uuid4().hex}_{filename}"
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], unique_filename)
        file.save(filepath)
        # Retornar la URL relativa para acceder a la imagen
        return url_for('static', filename=f'images/autoparts/{unique_filename}')
    return None

@app.route("/")
def index():
    if 'token' in session:
        return redirect(url_for("admin_dashboard"))
    return redirect(url_for("login"))

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        email = request.form.get("email")
        password = request.form.get("password")
        
        # Intentar login en la API de FastAPI
        try:
            response = requests.post(f"{API_URL}/auth/login", data={
                "username": email,
                "password": password
            })
            
            if response.status_code == 200:
                data = response.json()
                token = data.get("access_token")
                
                # Obtener info del usuario para validar rol
                user_response = requests.get(f"{API_URL}/auth/me", headers={
                    "Authorization": f"Bearer {token}"
                })
                
                if user_response.status_code == 200:
                    user_data = user_response.json()
                    # SOLO permitir ADMIN o EMPLOYEE en este portal interno
                    if user_data.get("role") in ["admin", "employee"]:
                        session['token'] = token
                        session['user_email'] = email
                        session['user_name'] = user_data.get("name")
                        session['user_role'] = user_data.get("role")
                        return redirect(url_for("admin_dashboard"))
                    else:
                        flash("Acceso denegado. Este portal es solo para personal interno.", "warning")
                else:
                    flash("Error al validar perfil de usuario.", "danger")
            else:
                flash("Credenciales incorrectas o acceso denegado.", "danger")
        except Exception as e:
            flash(f"Error de conexión con la API: {str(e)}", "danger")
            
    return render_template("auth/login.html")

@app.route("/logout")
def logout():
    session.clear()
    return redirect(url_for("login"))

def api_request(method, endpoint, data=None, params=None):
    if 'token' not in session:
        return None, 401
    
    headers = {"Authorization": f"Bearer {session['token']}"}
    url = f"{API_URL}{endpoint}"
    
    try:
        if method == "GET":
            response = requests.get(url, headers=headers, params=params)
        elif method == "POST":
            response = requests.post(url, headers=headers, json=data)
        elif method == "PUT":
            response = requests.put(url, headers=headers, json=data)
        elif method == "DELETE":
            response = requests.delete(url, headers=headers)
        return response.json(), response.status_code
    except Exception as e:
        return {"error": str(e)}, 500

@app.route("/admin")
@app.route("/admin/dashboard", endpoint="admin_dashboard")
@app.route("/dashboard")
def dashboard():
    # Obtener algunas estadísticas básicas para el dashboard
    autoparts, _ = api_request("GET", "/autoparts/")
    users, _ = api_request("GET", "/users/")
    orders, _ = api_request("GET", "/orders/all")
    
    # Obtener nombres de clientes para los pedidos recientes
    recent_orders = orders[-5:] if orders else []
    for order in recent_orders:
        user, _ = api_request("GET", f"/users/{order['user_id']}")
        order['user_name'] = user.get('name', 'N/A') if user else 'N/A'
    
    stats = {
        "total_autoparts": len(autoparts) if autoparts else 0,
        "total_users": len(users) if users else 0,
        "total_orders": len(orders) if orders else 0,
        "recent_orders": recent_orders
    }
    
    return render_template("admin/dashboard.html", active_page='dashboard', stats=stats)

@app.route("/admin/usuarios", endpoint="admin_usuarios")
@app.route("/usuarios")
def usuarios():
    users, status = api_request("GET", "/users/")
    if status == 401: return redirect(url_for("login"))
    return render_template("admin/usuarios.html", active_page='usuarios', users=users)

@app.route("/admin/usuarios/crear", methods=["POST"])
def usuarios_crear():
    data = {
        "name": request.form.get("name"),
        "email": request.form.get("email"),
        "password": request.form.get("password"),
        "role": request.form.get("role")
    }
    res, status = api_request("POST", "/users/", data=data)
    if status == 201 or status == 200:
        flash("Usuario creado exitosamente", "success")
    else:
        flash(f"Error al crear usuario: {res.get('detail', 'Error desconocido')}", "danger")
    return redirect(url_for("admin_usuarios"))

@app.route("/admin/usuarios/eliminar/<int:id>")
def usuarios_eliminar(id):
    res, status = api_request("DELETE", f"/users/{id}")
    if status == 200:
        flash("Usuario eliminado", "success")
    else:
        flash("Error al eliminar usuario", "danger")
    return redirect(url_for("admin_usuarios"))

@app.route("/admin/usuarios/editar/<int:id>", methods=["GET", "POST"])
def usuarios_editar(id):
    if request.method == "POST":
        data = {
            "name": request.form.get("name"),
            "email": request.form.get("email"),
            "role": request.form.get("role")
        }
        res, status = api_request("PUT", f"/users/{id}", data=data)
        if status == 200:
            flash("Usuario actualizado exitosamente", "success")
        else:
            flash(f"Error al actualizar: {res.get('detail', 'Error desconocido')}", "danger")
        return redirect(url_for("admin_usuarios"))
    
    user, status = api_request("GET", f"/users/{id}")
    if status != 200:
        flash("Usuario no encontrado", "danger")
        return redirect(url_for("admin_usuarios"))
    return render_template("admin/usuarios_editar.html", user=user, active_page='usuarios')

@app.route("/admin/autopartes", endpoint="admin_autopartes")
@app.route("/autopartes")
def autopartes():
    autoparts, status = api_request("GET", "/autoparts/")
    if status == 401: return redirect(url_for("login"))
    return render_template("admin/autopartes.html", active_page='autopartes', autoparts=autoparts)

@app.route("/admin/autopartes/crear", methods=["GET", "POST"], endpoint="admin_autopartes_crear")
def autopartes_crear():
    if request.method == "POST":
        # Manejar subida de imagen
        image_url = None
        if 'image' in request.files:
            file = request.files['image']
            if file.filename != '':
                image_url = save_uploaded_file(file)
                if not image_url:
                    flash("Error: Formato de imagen no válido. Use PNG, JPG, JPEG o GIF.", "danger")
                    return render_template("admin/autopartes_crear.html", active_page='autopartes')
        
        # Si no se subió imagen, usar una imagen por defecto
        if not image_url:
            image_url = url_for('static', filename='images/default-autopart.svg')
        
        data = {
            "name": request.form.get("name"),
            "description": request.form.get("description"),
            "price": float(request.form.get("price")),
            "stock": int(request.form.get("stock")),
            "category": request.form.get("category"),
            "image_url": image_url
        }
        res, status = api_request("POST", "/autoparts/", data=data)
        if status == 201 or status == 200:
            flash("Autoparte creada exitosamente", "success")
            return redirect(url_for("admin_autopartes"))
        else:
            flash(f"Error: {res.get('detail', 'Error desconocido')}", "danger")
            
    return render_template("admin/autopartes_crear.html", active_page='autopartes')

@app.route("/admin/autopartes/editar/<int:id>", methods=["GET", "POST"], endpoint="admin_autopartes_editar")
def autopartes_editar(id):
    if request.method == "POST":
        # Obtener datos actuales de la autoparte
        current_autopart, _ = api_request("GET", f"/autoparts/{id}")
        
        # Manejar subida de nueva imagen
        image_url = current_autopart.get("image_url")  # Mantener imagen actual por defecto
        if 'image' in request.files:
            file = request.files['image']
            if file.filename != '':
                new_image_url = save_uploaded_file(file)
                if new_image_url:
                    image_url = new_image_url
                else:
                    flash("Error: Formato de imagen no válido. Use PNG, JPG, JPEG o GIF.", "danger")
                    return render_template("admin/autopartes_editar.html", id=id, autopart=current_autopart, active_page='autopartes')
        
        data = {
            "name": request.form.get("name"),
            "description": request.form.get("description"),
            "price": float(request.form.get("price")),
            "stock": int(request.form.get("stock")),
            "category": request.form.get("category"),
            "image_url": image_url
        }
        res, status = api_request("PUT", f"/autoparts/{id}", data=data)
        if status == 200:
            flash("Autoparte actualizada", "success")
            return redirect(url_for("admin_autopartes"))
        else:
            flash("Error al actualizar", "danger")
            
    autopart, _ = api_request("GET", f"/autoparts/{id}")
    return render_template("admin/autopartes_editar.html", id=id, autopart=autopart, active_page='autopartes')

@app.route("/admin/autopartes/eliminar/<int:id>")
def autopartes_eliminar(id):
    res, status = api_request("DELETE", f"/autoparts/{id}")
    if status == 200:
        flash("Autoparte eliminada", "success")
    else:
        flash("Error al eliminar", "danger")
    return redirect(url_for("admin_autopartes"))

@app.route("/admin/inventario", endpoint="admin_inventario")
@app.route("/inventario")
def inventario():
    autoparts, status = api_request("GET", "/autoparts/")
    if status == 401: return redirect(url_for("login"))
    
    total_productos = len(autoparts) if autoparts else 0
    bajo_stock = sum(1 for p in autoparts if 0 < p.get('stock', 0) <= 10) if autoparts else 0
    agotados = sum(1 for p in autoparts if p.get('stock', 0) <= 0) if autoparts else 0

    stats = {
        "total": total_productos,
        "bajo_stock": bajo_stock,
        "agotados": agotados
    }

    return render_template("admin/inventario.html", active_page='inventario', autoparts=autoparts, stats=stats)

@app.route("/admin/reportes", endpoint="admin_reportes")
def admin_reportes():
    return render_template("admin/reportes.html", active_page='reportes')

@app.route("/admin/reportes/descargar/<path:report_type>")
def descargar_reporte(report_type):
    fmt = request.args.get("format", "xlsx")
    headers = {"Authorization": f"Bearer {session.get('token')}"}
    
    # Construir URL con todos los parámetros
    params = {"format": fmt}
    
    # Agregar todos los demás parámetros de la URL
    for key, value in request.args.items():
        if key != "format":
            params[key] = value
    
    try:
        response = requests.get(f"{API_URL}/reports/{report_type}", headers=headers, params=params, stream=True)
        if response.status_code == 200:
            from flask import Response
            return Response(
                response.iter_content(chunk_size=1024),
                content_type=response.headers['Content-Type'],
                headers={"Content-Disposition": response.headers['Content-Disposition']}
            )
        else:
            flash("Error al generar el reporte", "danger")
            return redirect(url_for("admin_reportes"))
    except Exception as e:
        flash(f"Error: {str(e)}", "danger")
        return redirect(url_for("admin_reportes"))

@app.route("/admin/pedidos", endpoint="admin_pedidos")
def admin_pedidos():
    orders, status = api_request("GET", "/orders/all")
    if status == 401: return redirect(url_for("login"))
    return render_template("admin/pedidos.html", active_page='pedidos', orders=orders)

@app.route("/admin/pedidos/<int:id>")
def admin_pedido_detalle(id):
    order, status = api_request("GET", f"/orders/{id}")
    if status == 401: return redirect(url_for("login"))
    if status != 200:
        flash("No se pudo obtener el detalle del pedido", "danger")
        return redirect(url_for("admin_pedidos"))
    
    # Obtener nombres de autopartes para el detalle
    for item in order.get('items', []):
        part, _ = api_request("GET", f"/autoparts/{item['autopart_id']}")
        item['autopart_name'] = part.get('name', 'N/A') if part else 'N/A'
    
    # Obtener nombre del usuario
    user, _ = api_request("GET", f"/users/{order['user_id']}")
    order['user_name'] = user.get('name', 'N/A') if user else 'N/A'
    
    return render_template("admin/pedido_detalle.html", order=order, active_page='pedidos')

@app.route("/admin/pedidos/<int:id>/status", methods=["POST"])
def admin_pedido_status(id):
    new_status = request.form.get("status")
    res, status = api_request("PATCH", f"/orders/{id}/status", params={"status": new_status})
    if status == 200:
        flash("Estado del pedido actualizado", "success")
    else:
        flash("Error al actualizar estado", "danger")
    return redirect(url_for("admin_pedido_detalle", id=id))

@app.route("/catalogo")
def catalogo():
    return render_template("legacy/catalogo.html", active_page='catalogo')

@app.route("/api/catalogo/autoparts")
def api_catalogo_autoparts():
    # Obtener autopartes desde la API principal
    autoparts, status = api_request("GET", "/autoparts/")
    if status == 200:
        # Obtener la URL base dinámicamente
        request_host = request.host_url.rstrip('/')
        
        # Procesar las URLs de imágenes para que sean accesibles
        for autopart in autoparts:
            if autopart.get('image_url'):
                image_url = autopart['image_url']
                if not image_url.startswith('http'):
                    # Si es una URL relativa, convertirla a absoluta
                    if image_url.startswith('/'):
                        autopart['image_url'] = f"{request_host}{image_url}"
                    else:
                        # Si es solo el nombre del archivo, construir la URL completa
                        autopart['image_url'] = f"{request_host}/static/images/autoparts/{image_url}"
            else:
                # Si no hay imagen, usar la imagen por defecto
                autopart['image_url'] = f"{request_host}/static/images/default-autopart.svg"
        return {"autoparts": autoparts}
    else:
        return {"error": "No se pudieron cargar las autopartes"}, 500

if __name__ == "__main__":
    app.run(debug=True, host='0.0.0.0')
