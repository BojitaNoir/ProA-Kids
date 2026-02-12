# HNM-VigiMed Mobile API Documentation (Flutter)

## 🌐 base URL
`http://10.10.16.97:8000/api`

## 🔑 Authentication
La API utiliza **Laravel Sanctum**. Para endpoints protegidos, incluya el token en el header:
`Authorization: Bearer {token}`

---

### 1. 🚪 Login (Token Request)
Obtiene el token de acceso y la información del usuario.

- **URL**: `/login`
- **Método**: `POST`
- **Parámetros**:
  ```json
  {
    "email": "usuario@vigimed.hnm.gob.mx",
    "password": "tu_password"
  }
  ```
- **Respuesta (200 OK)**:
  ```json
  {
    "token": "1|abc123identificadortoken...",
    "user": {
      "id": 1,
      "name": "Dr. Nombre Ejemplo",
      "email": "doctor@vigimed.hnm.gob.mx",
      "role": "doctor"
    }
  }
  ```

---

### 2. 📚 Biblioteca de Documentos
Lista todos los documentos (guías y diagnósticos) agrupados por área.

- **URL**: `/documentos`
- **Método**: `GET`
- **Respuesta (200 OK)**:
  ```json
  {
    "guias": [
      {
        "id": 1,
        "nombre": "Guía Vancomicina",
        "fecha": "2024-02-05",
        "download_url": "http://domain.com/documentos/1/download",
        "uploader": "Admin"
      }
    ],
    "diagnosticos": [
      {
        "id": 5,
        "nombre": "Protocolo Neumonía",
        "fecha": "2024-02-04",
        "download_url": "http://domain.com/documentos/5/download",
        "uploader": "Dr. Pedro"
      }
    ]
  }
  ```

---

### 3. 🧪 Herramientas Clínicas (Catálogos)
Para llenar los selectores del asistente de validación.

- **Endpoints**:
  - `GET /patologias`
  - `GET /medicamentos`

---

### 4. ✅ Validación HNM-VigiMed
Realiza el cálculo de función renal y verifica interacciones.

- **URL**: `/validar`
- **Método**: `POST`
- **Header Requerido**: `X-API-KEY: VIGIMED-2024-SECURE-KEY`
- **Payload**:
  ```json
  {
    "edad": 45,
    "peso": 75,
    "creatinina": 1.2,
    "genero": "masculino",
    "id_patologia": 1,
    "id_medicamento": 2
  }
  ```
