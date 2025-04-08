# 🎾 Torneo de Tenis - API REST

## 📋 Requisitos técnicos

- **PHP**: Versión 8.2 o superior
- **Base de datos**: MySQL 5.7+/MariaDB 10.3+
- **Composer**: Para gestión de dependencias

## 🚀 Instalación y configuración

### 1. Clonar el repositorio

    git clone https://github.com/MarioOlivera/Torneo-tenis-test.git
    cd Torneo-tenis-test

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar base de datos

- Crear una base de datos MySQL (ej: **tournament_db**)
- Importar la estructura inicial: **archivo db.sql**.

### 4. Configurar variables de entorno

```bash
cp .env.example .env
```

Editar el archivo **.env** con tu configuración

```bash
BASE_URL=http://localhost:8000
APP_ENV=development
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=tournament_db
```

### 5. ▶️ Ejecución del proyecto

Iniciar el servidor de desarrollo:

```bash
php -d max_execution_time=0 -S localhost:8000 -t public
```

### 6. Acceder a la documentación

```bash
http://localhost:8000/docs
```

### 7. Comandos composer

Generar/actualizar documentación Swagger:

```bash
composer run docs
```

### 📊 Modelo de base de datos

![N|Solid](https://github.com/MarioOlivera/Torneo-tenis-test/blob/main/model_db.png?raw=true)

# 🎾 ** Intrucciones del Challenge**

- La modalidad del torneo es por eliminación directa.
- Puede asumir por simplicidad que la cantidad de jugadores es potencia de 2.
- El torneo puede ser Femenino o Masculino.
- Cada jugador tiene un nombre y un nivel de habilidad (entre 0 y 100)
- En un enfrentamiento entre dos jugadores influyen el nivel de habilidad y la suerte para decidir al ganador del mismo. Es su decisión de diseño de que forma incide la suerte en este enfrentamiento.
- En el torneo masculino, se deben considerar la fuerza y la velocidad de desplazamiento como parámetros adicionales al momento de calcular al ganador.
- En el torneo femenino, se debe considerar el tiempo de reacción como un parámetro adicional al momento de calcular al ganador.
- No existen los empates.
- Se requiere que a partir de una lista de jugadores se simule el torneo y se obtenga como output al ganador del mismo.
- Se recomienda realizar la solución en su IDE preferido.
- Se valorarán las buenas practicas de Programación Orientada a Objetos.
- Puede definir por su parte cualquier cuestión que considere que no es aclarada.
- Puede agregar las aclaraciones que considere en la entrega del ejercicio.
- Cualquier extra que aporte será bienvenido.
- Se prefiere el modelado en capas o arquitecturas limpias (Clean Architecture)
- Se prefiere la entrega de la solución mediante un sistema de versionado (GitHub/Bitbucket/etc)

> La eliminación directa, es un sistema en torneos que consiste en que el perdedor de un encuentro queda inmediatamente eliminado de la competición, mientras que el ganador avanza a la siguiente fase. Se van jugando rondas y en cada una de ellas se elimina la mitad de participantes hasta dejar un único competidor que se corona como campeón.

**Importante**: Se prestara especial énfasis en el correcto modelado y aplicación de buenas prácticas de la programación orientada a objetos.

**Puntos extras:**
Apartado 1: Testing de la solución (Unit Test)
Apartado 2: API Rest (Swagger + Integration Testing)

- Con base en una lista de jugadores, retorna el resultado del torneo.
- Permite consultar el resultado de los torneos finalizados exitosamente con base en alguno de los siguientes criterios:
- Fecha
- Torneo Masculino y/o Femenino.
- Otros que usted considere.

Apartado 3: Utilizar una base de datos no embebida.
Apartado 4: Subir el código a un repositorio como GitLab/GitHub/etc.
Apartado 5: Subir el o los servicios a AWS/Azure/Etc utilizando Docker o kubernetes.
