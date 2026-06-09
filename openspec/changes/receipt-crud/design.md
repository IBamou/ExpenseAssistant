# Receipt CRUD Design

## Overview
Standard Laravel resource controller pattern for `receipts` under auth middleware. Receipts are immutable after creation (status transitions only via the AI extraction job). The design emphasizes ownership scoping, eager loading to prevent N+1, and status display via Blade enums.

## Architecture / Design Decisions

### Decision: Single Resource Controller
- **Context:** Receipt CRUD covers index, create/store, show, delete
- **Options considered:** Separate controllers for each action, invokable controllers
- **Chosen approach:** Single `ReceiptController` with `index`, `store`, `show`, `destroy` methods. `edit`/`update` are excluded — receipts are immutable after submission.
- **Trade-offs:** None significant. Keeps related actions colocated.
- **Impact:** `app/Http/Controllers/ReceiptController.php`

### Decision: Form Request for Validation
- **Context:** Receipt submission must validate `source_text` before any AI call
- **Options considered:** Inline validation in controller, Form Request
- **Chosen approach:** Dedicated `StoreReceiptRequest` class — `source_text` required, string, min:10, max:10000
- **Trade-offs:** Slightly more files, but isolates validation logic and makes testing easier.
- **Impact:** `app/Http/Requests/StoreReceiptRequest.php`

### Decision: Ownership Scoping via Controller
- **Context:** Users must only see/delete their own receipts
- **Options considered:** Global scope on model, policy, controller-level scoping
- **Chosen approach:** Controller queries scoped to `auth()->id()` — `Receipt::where('user_id', auth()->id())` for all queries. Gate/Policy can be added later if authorization logic grows.
- **Trade-offs:** Simple and explicit. Not as reusable as a Policy, but sufficient for this scope.
- **Impact:** All controller methods scope by `user_id`

### Decision: Eager Loading Strategy
- **Context:** Index needs expense count, show needs expense list
- **Options considered:** Lazy loading, `withCount`, `with`
- **Chosen approach:** Index uses `withCount('expenses')`, show uses `with('expenses')`
- **Trade-offs:** Two queries instead of one for show, but avoids N+1 on both pages.
- **Impact:** Controller methods use explicit eager loading.

### Decision: Flash Messaging for Submission
- **Context:** User needs immediate feedback after pasting a receipt
- **Options considered:** Toast notifications, session flash, livewire events
- **Chosen approach:** Standard Laravel `session()->flash('status', 'Receipt submitted for processing.')` — consistent with Breeze patterns. No Alpine.js toast component needed.
- **Impact:** Create view checks for `session('status')` and shows a green alert.

### Decision: Status Display via Enum
- **Context:** Receipt status must be displayed as a formatted badge
- **Options considered:** String comparison in Blade, Enum helper
- **Chosen approach:** `ReceiptStatus` PHP enum with a `label()` method returning translated labels, and Blade partial for badge colors.
- **Impact:** `app/Enums/ReceiptStatus.php`, reused by the AI extraction change.

### Decision: Cascade Delete via Migration
- **Context:** Deleting a receipt must remove its expenses
- **Options considered:** Model `deleting` event, foreign key cascade
- **Chosen approach:** `$table->foreignId('receipt_id')->constrained()->cascadeOnDelete()` in the expenses migration
- **Trade-offs:** Database-level cascade is safest and requires no code. The receipt model and expense model reference this change, but the expenses migration is part of this changeset.
- **Impact:** Expenses migration created in this change.

## Implementation Plan
1. Write OpenSpec artifacts (proposal, spec, design, tasks)
2. Create `app/Enums/ReceiptStatus.php`
3. Create migration for `receipts` table
4. Create migration for `expenses` table (with FK cascade)
5. Create `app/Models/Receipt.php` with casts and relationships
6. Create `app/Models/Expense.php` with casts and relationships
7. Add `hasMany(Receipt)` to `User` model
8. Create `app/Http/Requests/StoreReceiptRequest.php`
9. Create `app/Http/Controllers/ReceiptController.php`
10. Add resourceful routes to `routes/web.php`
11. Create views: `index.blade.php`, `create.blade.php`, `show.blade.php`
12. Add "My Receipts" navigation link
13. Update dashboard view
14. Run migrations + verify

## Data Model / Schema Changes

### receipts table
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint (PK) | auto-increment |
| user_id | bigint (FK) | references users, not nullable |
| source_text | text | not nullable |
| status | string(20) | default 'pending', enum validated in model cast |
| raw_ai_payload | json | nullable |
| expenses_count | integer | default 0 |
| created_at | timestamp | |
| updated_at | timestamp | |

### expenses table
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint (PK) | auto-increment |
| receipt_id | bigint (FK) | references receipts, cascade on delete |
| label | string(255) | not nullable |
| quantity | integer | not nullable |
| unit_price | decimal(10,2) | not nullable |
| category | string(20) | enum validated in model cast |
| created_at | timestamp | |
| updated_at | timestamp | |

## API / Routes
| Method | URI | Action | Middleware |
|--------|-----|--------|-----------|
| GET | /receipts | ReceiptController@index | auth, verified |
| GET | /receipts/create | ReceiptController@create | auth, verified |
| POST | /receipts | ReceiptController@store | auth, verified |
| GET | /receipts/{receipt} | ReceiptController@show | auth, verified |
| DELETE | /receipts/{receipt} | ReceiptController@destroy | auth, verified |

## Security Considerations
- Users cannot access receipts belonging to other users (controller scoping by `user_id`)
- Delete authorization: only the owning user can delete (implicit via scope)
- CSRF protection via Laravel's default `@csrf` on forms

## Performance Considerations
- Index page uses `withCount('expenses')` — one extra COUNT query total
- Show page uses `with('expenses')` — two queries total (receipt + expenses)
- Both are negligible at expected scale (hundreds, not millions of receipts)

## Testing Strategy
- Test receipt creation with valid/invalid text
- Test receipt listing returns only user's receipts
- Test receipt show requires ownership
- Test receipt delete removes receipt and expenses
- Test validation errors from StoreReceiptRequest
