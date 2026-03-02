from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        return redirect(url_for("dashboard"))
    return render_template("auth/login.html")

@app.route("/dashboard")
def dashboard():
    return render_template("dashboard.html", active_page='dashboard')

@app.route("/pedidos")
def pedidos():
    return render_template("dashboard.html", active_page='pedidos')

@app.route("/pedido/<int:pedido_id>")
def pedido_detalle(pedido_id):
    return render_template("pedido_detalle.html", pedido_id=pedido_id, active_page='pedidos')

@app.route("/reportes")
def reportes():
    return render_template("reportes.html", active_page='reportes')

if __name__ == "__main__":
    app.run(debug=True)