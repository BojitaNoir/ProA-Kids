# 🏥 PROA - Plataforma de Validación Médica

Sistema de validación clínica y cálculo de función renal desarrollado con Laravel 10/11.

## 📋 Descripción

PROA (Programa de Optimización de Antimicrobianos) es una plataforma médica que gestiona:

- ✅ **Validaciones clínicas** de prescripciones médicas
- 🫘 **Cálculo de función renal** mediante la fórmula de Cockcroft-Gault
- 🚨 **Alertas de seguridad** para combinaciones peligrosas (ej: Dengue + AINEs)
- 💊 **Prevención de nefrotoxicidad** mediante ajuste de dosis según ClCr

## 🎯 Importancia Clínica

### ⚠️ Prevención de Hemorragia en Dengue

El sistema detecta automáticamente la combinación **Dengue + Ibuprofeno (AINE)**, que está **ABSOLUTAMENTE CONTRAINDICADA** debido a:

- El Dengue causa trombocitopenia (disminución de plaquetas)
- Los AINEs tienen efecto antiagregante plaquetario
- Esta combinación puede causar hemorragia severa potencialmente mortal

### 🫘 Prevención de Nefrotoxicidad

El cálculo del **Aclaramiento de Creatinina (ClCr)** mediante Cockcroft-Gault permite:

- Detectar insuficiencia renal antes de prescribir
- Ajustar dosis según función renal
- Prevenir acumulación de fármacos nefrotóxicos
- Evitar daño renal adicional

**Fórmula de Cockcroft-Gault:**
```
ClCr (ml/min) = [(140 - edad) × peso] / (72 × creatinina sérica)
Si es mujer: multiplicar por 0.85
```

## 🚀 Instalación

### Requisitos Previos

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js >= 16 (opcional, para assets)

### Pasos de Instalación

1. **Clonar o copiar el proyecto**
   ```bash
   cd "c:\Users\andre\Downloads\Documents\ProAnet WEB"
   ```

2. **Instalar dependencias de Composer**
   ```bash
   composer install
   ```

3. **Configurar el archivo .env**
   ```bash
   copy .env.example .env
   ```

4. **Editar .env con tus credenciales de base de datos:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=proa_db
   DB_USERNAME=root
   DB_PASSWORD=tu_password
   
   PROA_API_KEY=PROA-2024-SECURE-KEY
   ```

5. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

6. **Crear la base de datos**
   ```sql
   CREATE DATABASE proa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

7. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

8. **Ejecutar seeders**
   ```bash
   php artisan db:seed --class=ProaSeeder
   ```

9. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

10. **Acceder a la aplicación**
    ```
    http://localhost:8000
    ```

## 📁 Estructura del Proyecto

```
ProAnet WEB/
├── app/
│   ├── Http/Controllers/
│   │   └── ValidacionController.php    # Lógica de validación y ClCr
│   └── Models/
│       ├── Patologia.php               # Modelo de patologías
│       ├── Medicamento.php             # Modelo de medicamentos
│       └── AlertaPeligro.php           # Modelo de alertas
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_patologias_table.php
│   │   ├── 2024_01_01_000002_create_medicamentos_table.php
│   │   └── 2024_01_01_000003_create_alertas_peligro_table.php
│   └── seeders/
│       └── ProaSeeder.php              # Datos iniciales
├── resources/views/proa/
│   └── index.blade.php                 # Interfaz de usuario
├── routes/
│   ├── api.php                         # Rutas API
│   └── web.php                         # Rutas web
└── config/
    └── app.php                         # Configuración (API Key)
```

## 🔌 API Endpoints

### POST /api/validar

Valida una prescripción médica y calcula el ClCr.

**Headers:**
```
X-API-KEY: PROA-2024-SECURE-KEY
Content-Type: application/json
```

**Request Body:**
```json
{
  "edad": 45,
  "peso": 70.5,
  "creatinina": 1.2,
  "genero": "masculino",
  "id_patologia": 1,
  "id_medicamento": 2
}
```

**Response (Sin alertas):**
```json
{
  "validacion_exitosa": true,
  "paciente": {
    "edad": 45,
    "peso": 70.5,
    "genero": "masculino",
    "creatinina_serica": 1.2
  },
  "funcion_renal": {
    "clcr_ml_min": 78.47,
    "categoria": "Levemente disminuida",
    "interpretacion": "Función renal levemente disminuida..."
  },
  "prescripcion": {
    "patologia": "Dengue",
    "medicamento": "Paracetamol",
    "familia_medicamento": "Analgésico"
  }
}
```

**Response (Con alerta crítica):**
```json
{
  "validacion_exitosa": false,
  "alerta_seguridad": {
    "nivel": "CRÍTICO",
    "mensaje": "⚠️ ALERTA CRÍTICA DE SEGURIDAD: El uso de Ibuprofeno (AINE) está CONTRAINDICADO...",
    "recomendacion": "NO PRESCRIBIR. Consultar con médico especialista."
  },
  ...
}
```

### GET /api/patologias

Obtiene el catálogo de patologías.

### GET /api/medicamentos

Obtiene el catálogo de medicamentos.

## 🎨 Interfaz de Usuario

La interfaz utiliza el esquema de colores médico profesional:

- **Color principal:** `#00548F` (Azul médico)
- **Color secundario:** `#0077B6` (Azul claro)
- **Alertas:**
  - 🟢 **Verde:** Validación exitosa
  - 🟡 **Amarillo:** Advertencia (función renal disminuida)
  - 🔴 **Rojo:** Alerta crítica (contraindicación absoluta)

## 🔒 Seguridad

- **API Key:** Todos los endpoints de validación requieren el header `X-API-KEY`
- **Validación de datos:** Validación estricta de todos los parámetros de entrada
- **Sanitización:** Protección contra inyección SQL mediante Eloquent ORM

## 🧪 Datos de Prueba

El seeder incluye:

**Patologías:**
- Dengue (con alerta de hemorragia)
- Migraña

**Medicamentos:**
- Paracetamol (Analgésico) - Seguro para Dengue ✅
- Ibuprofeno (AINE) - **CONTRAINDICADO** en Dengue ❌

**Alerta configurada:**
- Dengue + Ibuprofeno = ALERTA CRÍTICA 🚨

## 📊 Casos de Uso

### Caso 1: Prescripción Segura
```
Paciente: 45 años, 70kg, masculino
Creatinina: 1.0 mg/dL
Patología: Dengue
Medicamento: Paracetamol
Resultado: ✅ VALIDACIÓN EXITOSA
ClCr: 94.44 ml/min (Normal)
```

### Caso 2: Alerta Crítica
```
Paciente: 45 años, 70kg, masculino
Creatinina: 1.0 mg/dL
Patología: Dengue
Medicamento: Ibuprofeno
Resultado: 🚨 ALERTA CRÍTICA - NO PRESCRIBIR
```

### Caso 3: Advertencia Renal
```
Paciente: 75 años, 55kg, femenino
Creatinina: 2.5 mg/dL
Patología: Migraña
Medicamento: Paracetamol
Resultado: ⚠️ Función renal severamente disminuida
ClCr: 18.7 ml/min - Ajustar dosis
```

## 🛠️ Personalización

### Agregar nuevas patologías

```php
DB::table('patologias')->insert([
    'nombre' => 'Hipertensión',
    'alerta_especifica' => 'Monitorear presión arterial.',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

### Agregar nuevos medicamentos

```php
DB::table('medicamentos')->insert([
    'nombre' => 'Aspirina',
    'familia' => 'AINE',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

### Agregar nuevas alertas

```php
DB::table('alertas_peligro')->insert([
    'patologia_id' => 1,
    'medicamento_id' => 3,
    'mensaje_error' => 'Mensaje de alerta personalizado',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

## 📝 Licencia

Este proyecto es de uso educativo y médico.

## 👨‍⚕️ Soporte

Para soporte técnico o consultas médicas sobre el sistema, contactar al equipo de desarrollo.

---

**Desarrollado con ❤️ para mejorar la seguridad del paciente**
