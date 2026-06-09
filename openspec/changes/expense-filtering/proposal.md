# Expense Filtering Proposal

## Summary
Add a filterable expense list page showing all expenses across all receipts, filterable by category, so Brahim can see how much he spends on Food vs. Beverages vs. Hygiene at a glance.

## Motivation
Brahim currently has to open each receipt individually to see expenses. He needs a consolidated view across all his receipts with category filtering to understand his spending patterns.

## What Changes

**[New Capability: expense-filtering]**
- From: Expenses are only visible inside individual receipt show pages
- To: A new `/expenses` page lists all expenses with category filter tabs, showing label, quantity, unit price, category, and receipt date
- Reason: Category-level tracking is the key insight Brahim needs from his data
- Impact: Non-breaking. Adds new Livewire component, route, and nav link.

## Scope
### In Scope
- Livewire full-page component for expense list
- Category filter buttons (Food / Beverages / Hygiene / Maintenance / Other)
- Paginated expense list with receipt source reference
- Navigation link in sidebar

### Out of Scope / Non-Goals
- Date range filtering
- Export/CSV download
- Chart/visualization

## Impact Analysis
- **Affected capabilities:** receipt-crud (adds nav link, no changes to existing pages)
- **Breaking changes:** No
