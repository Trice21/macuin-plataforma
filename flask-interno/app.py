from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)

@app.route("/")
def index():
    return redirect(url_for("login"))

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        return redirect(url_for("admin_dashboard"))
    return render_template("auth/login.html")


@app.route("/admin")
@app.route("/admin/dashboard", endpoint="admin_dashboard")
@app.route("/dashboard")
def dashboard():
    return render_template("admin/dashboard.html", active_page='dashboard')

@app.route("/pedidos", endpoint="employee_pedidos")
def pedidos():
    return render_template("employee/pedidos.html", active_page='pedidos')

@app.route("/pedido/<int:pedido_id>", endpoint="employee_pedido_detalle")
def pedido_detalle(pedido_id):
    return render_template("employee/pedido_detalle.html", pedido_id=pedido_id, active_page='pedidos')

@app.route("/admin/reportes", endpoint="admin_reportes")
@app.route("/reportes")
def reportes():
    return render_template("admin/reportes.html", active_page='reportes')

@app.route("/admin/autopartes", endpoint="admin_autopartes")
@app.route("/autopartes")
def autopartes():
    return render_template("admin/autopartes.html", active_page='autopartes')

@app.route("/admin/inventario", endpoint="admin_inventario")
@app.route("/inventario")
def inventario():
    return render_template("admin/inventario.html", active_page='inventario')

@app.route("/admin/usuarios", endpoint="admin_usuarios")
@app.route("/usuarios")
def usuarios():
    return render_template("admin/usuarios.html", active_page='usuarios')

@app.route("/catalogo")
def catalogo():
    return render_template("catalogo.html", active_page='catalogo')

if __name__ == "__main__":
    app.run(debug=True, host='0.0.0.0')
