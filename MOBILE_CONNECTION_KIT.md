# 📱 KIT DE CONEXIÓN MÓVIL - PROA

Este archivo contiene TODO lo necesario para conectar la app móvil con el backend local.

---

## 1. 📡 CONFIGURACIÓN DE RED (IP LOCAL)
Para que el celular vea al sevidor, ambos deben estar en la misma red Wi-Fi.

- **Tu Dirección IP**: `10.10.16.97`
- **Puerto**: `8000`
- **BASE URL PARA LA APP**: 
  ```
  http://10.10.16.97:8000/api
  ```

---

## 2. 🚀 COMANDO DE INICIO (Backend)
Ejecuta esto en tu terminal para encender el servidor y permitir conexiones externas:

```powershell
php artisan serve --host=0.0.0.0 --port=8000
```

*(Si no usas este comando exacto, el celular NO podrá conectarse)*

---

## 3. 🔑 CREDENCIALES DE PRUEBA
Usa estos usuarios para probar el Login en la app:

| Rol      | Email                         | Password |
|----------|-------------------------------|----------|
| **Médico** | `juan@vigimed.hnm.gob.mx`     | `password` |
| **Admin**  | `admin@vigimed.hnm.gob.mx`    | `password` |

---

## 4. 🛠️ SOLUCIÓN DE PROBLEMAS

**Si la app dice "Network Error" o no conecta:**

1.  **Firewall de Windows**: Es la causa #1. 
    - Desactívalo temporalmente o permite la conexión en el puerto 8000.
2.  **Misma Red**: Asegúrate que el celular NO esté con datos móviles, sino en el mismo Wi-Fi (`haena.local`).
3.  **Ping Test**: Desde una terminal en otra PC o celular (usando una app de terminal), intenta hacer ping a `10.10.16.97`. Si no responde, es el Firewall.

---

## 5. 🔌 ENDPOINTS PRINCIPALES

**Headers Requeridos:**
- `Accept: application/json`
- `Content-Type: application/json`
- `X-API-KEY: VIGIMED-2024-SECURE-KEY` (Solo para `/validar`)

| Método | Endpoint      | Descripción                          |
|--------|---------------|--------------------------------------|
| POST   | `/login`      | Autenticación (Retorna Token)        |
| GET    | `/documentos` | Lista de Guías y Protocolos          |
| POST   | `/validar`    | Validación Clínica (Requiere API Key)|

---
*Generado automáticamente por Antigravity - PROA System*
