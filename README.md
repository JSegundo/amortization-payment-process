# GoParity Coding Challenge: Amortization Payment Process

A Laravel service to process amortization payments.

## Introduction

I've added two new columns to two out of the three main tables: `promoter_email` and `profile_email`.

## Schema

### Projects

- `id` (Primary Key)
- `wallet_balance`
- `promoter`
- `promoter_email`

### Amortizations

- `id` (Primary Key)
- `project_id` (Foreign Key -> `Projects.id`)
- `schedule_date`
- `state`
- `amount`

### Payments

- `id` (Primary Key)
- `amortization_id` (Foreign Key -> `Amortizations.id`)
- `amount`
- `state`
  `profile_id`
- `profile_email`

## Additional Features

I've created another branch called `full-project` where I've implemented:
- Database migrations to create the tables
- Factories for generating fake data
- Seeders for populating the database

## Code Snippets

### Retrieving Amortizations

I have created routes and controllers to handle various conditions for retrieving amortizations. One of them also feeds data to a Vue.js dashboard.

```
public function getAmortizations()
{
    $amortizations = Amortization::select(['schedule_date', 'state', 'amount', 'project_id'])
                                 ->get()
                                 ->toArray();
    return response()->json($amortizations);
}
output example
[
  {
    "schedule_date": "2023-06-21 07:24:35",
    "state": "pending",
    "amount": 1388,
    "project_id": 9
  },
  {
    "schedule_date": "2023-05-01 09:26:19",
    "state": "paid",
    "amount": 2659,
    "project_id": 10
  },
  ...
]
```

Scheduled Payments
The payAmortizations function can be scheduled as a daily cron job.

```
protected function schedule(Schedule $schedule)
{
    $schedule->call(function(){
        $date = now();
        PaymentService::payAmortizations($date);
    })->daily();
}
```
