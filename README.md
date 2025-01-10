# Test Paycash con Docker

Link de la url desplegada en AWS: https://k6yxy5gbs5.execute-api.us-east-2.amazonaws.com/prod/api/

Este proyecto utiliza Laravel Sail para proporcionar un entorno de desarrollo basado en Docker.

## Requisitos

-   Docker Desktop instalado y ejecutándose.
-   Composer instalado globalmente.

## Instalación

1. **Clonar el repositorio:**

    ```bash
    git clone https://github.com/pikelito/test-paycash-backend
    cd test-paycash-backend
    ```

2. **Instalar las dependencias de Composer:**

    ```bash
    composer install
    ```

3. **Copiar el archivo de entorno:**

    ```bash
    cp .env.example .env
    ```

4. **Iniciar los contenedores:**

    ```bash
    ./vendor/bin/sail up -d
    ```

5. **Generar la clave de la aplicación:**

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6. **Ejecutar las migraciones:**

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

## Acceso a la aplicación

-   **Aplicación Laravel:** [http://localhost:8000](http://localhost:8000)
-   **API Docs:** [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

## Comandos útiles

-   **Detener los contenedores:**

    ```bash
    ./vendor/bin/sail down
    ```

-   **Ver logs de los contenedores:**

    ```bash
    ./vendor/bin/sail logs
    ```

-   **Ejecutar comandos de Artisan:**

    ```bash
    ./vendor/bin/sail artisan <comando>
    ```

-   **Ejecutar Composer:**

    ```bash
    ./vendor/bin/sail composer <comando>
    ```

## Notas

-   Asegúrate de que los puertos configurados en el archivo `.env` no estén siendo utilizados por otros servicios en tu máquina.
-   Si experimentas problemas de conexión, verifica que Docker Desktop esté ejecutándose y que los contenedores estén activos.
