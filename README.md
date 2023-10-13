# amortization-payment-process - GoParity coding challenge
service to process amortizacion payments - laravel

I added 2 columns to 2 of the 3 main columns: - promoter_email , - profile_email

## Projects
- id (PK)
- wallet_balance
- promoter
- promoter_email

## Amortizations
- id (PK)
- project_id (FK -> [Projects.id])
- schedule_date
- state
- amount

## Payments
- id (PK)
- amortization_id (FK -> [Amortizations.id])
- amount
- state
- profile_email

