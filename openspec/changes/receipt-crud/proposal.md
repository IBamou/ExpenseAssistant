# Receipt CRUD Proposal

## Summary
Add CRUD capabilities for supplier receipts — list, submit (paste text), view details with expenses, and delete — all scoped to the authenticated user.

## Motivation
Brahim needs a simple tool to track his supplier receipts. The core loop is: paste receipt text → AI extracts expenses → review results. This change delivers the "paste" and "review" surfaces without which the AI extraction has no UI to hook into.

## What Changes

**[New Capability: receipt-crud]**
- From: (not present)
- To: Authenticated users can list their receipts with status and expense count, submit a new receipt via text input, view receipt details including extracted expenses, and delete receipts with cascaded expenses.
- Reason: Users need full CRUD to interact with the receipt lifecycle.
- Impact: Non-breaking. Adds new routes, views, and controller. No existing behavior is modified.

## Scope
### In Scope
- Receipt index page (paginated list, status badges, expense count)
- Receipt create page (textarea for pasting text, validation, flash message)
- Receipt show page (source text, status, expense list)
- Receipt delete with cascade (expenses removed)
- Ownership scoping (users only see their own receipts)
- Form request validation for receipt submission

### Out of Scope / Non-Goals
- AI extraction logic (separate change: `ai-extraction`)
- Image upload for receipts
- Expense filtering or category breakdown
- Editing/updating receipts (status is immutable after creation)
- Dashboard or aggregate statistics

## Impact Analysis
- **Affected capabilities:** None (greenfield)
- **Affected users:** All authenticated users
- **Breaking changes:** No
- **Rollback plan:** Revert the merge and delete the migration
