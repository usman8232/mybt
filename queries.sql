SELECT 
    i.username,
    i.month,
    i.year,
    IFNULL(SUM(i.amount), 0) AS total_income,
    IFNULL(e.total_expenses, 0) AS total_expenses,
    IFNULL(SUM(i.amount), 0) - IFNULL(e.total_expenses, 0) AS savings
FROM 
    incomes i
LEFT JOIN 
    (
        SELECT username, month, year, SUM(amount) AS total_expenses
        FROM expenses
        GROUP BY username, month, year
    ) e 
    ON i.username = e.username AND i.month = e.month AND i.year = e.year
WHERE 
    i.username = 'umar123' AND i.month = 'Jully' AND i.year = '2025'
GROUP BY 
    i.username, i.month, i.year, e.total_expenses;
    