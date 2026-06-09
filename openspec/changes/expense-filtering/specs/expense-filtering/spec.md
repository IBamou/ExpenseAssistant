# Expense Filtering Specification

## Purpose
Authenticated users can view all their extracted expenses in a single list, filtered by category, to track spending patterns across receipts.

## Requirements

### Requirement: List-all-expenses
The system SHALL display a paginated list of all expenses belonging to the authenticated user.

#### Scenario: View expense list
- **GIVEN** the user is authenticated and has processed receipts with expenses
- **WHEN** they navigate to `/expenses`
- **THEN** they see a paginated table of expenses with: label, quantity, unit price, category, and receipt submission date

#### Scenario: Empty expense list
- **GIVEN** the user is authenticated and has no processed expenses
- **WHEN** they navigate to `/expenses`
- **THEN** they see an empty state message

### Requirement: Filter-expenses-by-category
The system SHALL allow filtering the expense list by category.

#### Scenario: Filter by single category
- **GIVEN** the user is authenticated and has expenses in multiple categories
- **WHEN** they click a category filter button
- **THEN** only expenses matching that category are shown

#### Scenario: Show all expenses
- **GIVEN** the user is authenticated and has filtered by a category
- **WHEN** they click "All" filter
- **THEN** all expenses are shown again
