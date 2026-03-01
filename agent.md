1. Visión General

Aplicación SaaS de finanzas personales construida con Laravel 12 y Filament v5. El sistema permite gestionar billeteras compartidas, tarjetas de crédito con Meses Sin Intereses (MSI) y auditoría detallada de gastos por usuario, bajo una arquitectura escalable, datos encriptados y **soporte nativo multi-idioma (i18n)**.

2. Arquitectura de Software (Escalabilidad)

    Action Pattern: Clases de responsabilidad única en app/Actions para procesos que cambian el estado (ej. CreateTransactionAction, ProcessInstallmentAction).
    Service Layer: Clases en app/Services para cálculos complejos y lógica de negocio (ej. FinanceReportingService).
    DTOs: Uso de Data Transfer Objects para mover datos limpios entre Filament y los Actions.
    Identificadores: Uso estricto de ULIDs para todas las llaves primarias (seguridad por oscuridad y escalabilidad).

3. Seguridad y Confidencialidad

    Database Encryption: Los campos description, amount, note y card_number deben usar el casting encrypted de Laravel.
    Data Isolation (Multitenancy): * Toda consulta debe estar filtrada por wallet_id mediante un Global Query Scope.
        Un usuario solo puede acceder a recursos vinculados a través de la tabla pivote wallet_user.
    Audit Log: Registro obligatorio de quién creó o editó cada gasto (created_by).

4. Modelos y Base de Datos (Esquema Sugerido)
A. Core & Multi-usuario

    User: id, name, email, password, **locale** (default: 'es').
    Wallet: id (ulid), name, currency.
    WalletUser (Pivote): wallet_id, user_id, role (owner, contributor).
    Invitation: email, wallet_id, token, expires_at.

B. Cuentas y Tarjetas
    Account: id, wallet_id, name, type (cash, debit, savings), balance (encrypted).
    CreditCard: id, account_id, limit (encrypted), closing_day, due_day, is_visible_to_contributors (boolean).

C. Movimientos y MSI

    Transaction: id (ulid), account_id, category_id, merchant_id, user_id (autor), amount (encrypted), type (income, expense, transfer), is_shared (boolean).
    Merchant: id, name, default_category_id. (Para agrupar compras como "Amazon", "Walmart").
    InstallmentPurchase (MSI): id, transaction_id, total_installments, remaining_installments, day_of_month.

5. Reglas de Negocio Críticas

    Procesamiento de MSI: * Al crear una compra a MSI, el Action debe generar la primera transacción y programar las sub-transacciones futuras o crear una tarea programada (Scheduled Task) que las genere cada mes.
    Billeteras Compartidas:
        Los reportes deben permitir filtrar "Gasto por Usuario" para ver quién consume más presupuesto.
        Si una transacción es is_shared = false, solo el autor puede ver los detalles sensibles, aunque esté en una billetera compartida.

    Transferencias:
        Una transferencia entre cuentas no es un gasto, es un movimiento doble (Out de Cuenta A, In en Cuenta B) vinculado por un transfer_id.

6. UI con Filament (Lineamientos)

    **Internacionalización (i18n):**
        Uso estricto de `__('cadena')` o `trans()` en todos los labels, placeholders y notificaciones.
        Los modelos deben usar nombres de atributos traducibles para los encabezados de tablas y formularios.

    Dashboards:
        Widget de "Gasto compartido vs Personal" (Pie Chart).
        Widget de "Próximos Vencimientos de Tarjetas".

    Resources:
        TransactionResource: Debe mostrar el autor del gasto y permitir filtrado por Tags y Merchants.
        Utilizar Action Buttons personalizados para "Conciliar Gasto" o "Dividir Cuenta".

7. Stack Tecnológico (Docker Context)

    PHP 8.3-fpm
    MariaDB 10.11 (Para soporte nativo de JSON y ULIDs).
    Redis: Para manejo de colas de procesamiento de reportes pesados y MSI.
    Nginx: Servidor web.
