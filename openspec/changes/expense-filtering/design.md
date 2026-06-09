# Expense Filtering Design

## Overview
A Livewire full-page component (`Expenses\Index`) lists all expenses belonging to the authenticated user, with category filter tabs. Expenses are joined with their parent receipt for date reference.

## Architecture / Design Decisions

### Decision: Livewire full-page component
- **Context:** Consistent with the existing receipt UI pattern
- **Chosen approach:** `App\Livewire\Expenses\Index` with `#[Layout('layouts.app')]`
- **Impact:** New component file + view

### Decision: Eager loading to prevent N+1
- **Context:** Each expense needs its receipt's `created_at` for display
- **Chosen approach:** `Expense::where(...)->with('receipt')` with select scoped to only needed columns
- **Trade-offs:** One extra query per page load, but avoids N+1

### Decision: Category filter via query string
- **Context:** Filter state should survive page navigations
- **Chosen approach:** Livewire `$categoryFilter` property with `filterByCategory()` method, same pattern as receipt status filter
- **Impact:** Consistent with `Receipts\Index` pattern

## Implementation Plan
1. Write OpenSpec artifacts
2. Create `app/Livewire/Expenses/Index.php`
3. Create `resources/views/livewire/expenses/index.blade.php`
4. Add route in `routes/web.php`
5. Add sidebar nav link in `layouts/app.blade.php`

## API / Routes
| Method | URI | Action | Middleware |
|--------|-----|--------|-----------|
| GET | /expenses | Expenses\Index | auth, verified |

## Testing Strategy
- Test listing returns only user's expenses
- Test category filter scopes results correctly
- Test empty state when no expenses exist
