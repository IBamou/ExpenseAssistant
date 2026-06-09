# Receipt CRUD Specification

## Purpose
Authenticated users can manage their supplier receipts — submit raw text, track processing status, review extracted expenses, and delete receipts.

## Requirements

### Requirement: List-user-receipts
The system SHALL display a paginated list of all receipts belonging to the authenticated user.

#### Scenario: View receipt list
- **GIVEN** the user is authenticated
- **WHEN** they navigate to `/receipts`
- **THEN** they see a paginated table of their receipts
- **AND** each row shows the receipt status as a formatted badge (Pending / Processed / Failed)
- **AND** each row shows the extracted expense count

#### Scenario: Empty receipt list
- **GIVEN** the user is authenticated and has no receipts
- **WHEN** they navigate to `/receipts`
- **THEN** they see an empty state message with a link to submit their first receipt

### Requirement: Submit-receipt-text
The system SHALL accept raw receipt text and create a receipt record with `pending` status.

#### Scenario: Submit valid receipt text
- **GIVEN** the user is authenticated
- **WHEN** they submit text of at least 10 characters via the create form
- **THEN** a new Receipt is created with status `pending`
- **AND** the user is redirected to the receipt list
- **AND** they see a flash message "Receipt submitted for processing"

#### Scenario: Submit empty or too-short text
- **GIVEN** the user is authenticated
- **WHEN** they submit text shorter than 10 characters
- **THEN** the form is rejected with a validation error
- **AND** no receipt is created

#### Scenario: Submit overly long text
- **GIVEN** the user is authenticated
- **WHEN** they submit text exceeding 10,000 characters
- **THEN** the form is rejected with a validation error
- **AND** no receipt is created

### Requirement: View-receipt-details
The system SHALL display a single receipt with its source text, status, and all extracted expenses.

#### Scenario: View processed receipt
- **GIVEN** the user is authenticated
- **AND** they own a receipt with status `processed`
- **WHEN** they navigate to `/receipts/{receipt}`
- **THEN** they see the original source text (read-only)
- **AND** they see the status as a formatted badge
- **AND** they see a table of extracted expenses with label, quantity, unit price, and category

#### Scenario: View pending receipt
- **GIVEN** the user is authenticated
- **AND** they own a receipt with status `pending`
- **WHEN** they navigate to `/receipts/{receipt}`
- **THEN** they see the source text and status
- **AND** they see a notice that extraction is still in progress

#### Scenario: View receipt owned by another user
- **GIVEN** the user is authenticated
- **WHEN** they attempt to view a receipt belonging to another user
- **THEN** they receive a 404 response

### Requirement: Delete-receipt
The system SHALL delete a receipt and all its associated expenses.

#### Scenario: Delete own receipt
- **GIVEN** the user is authenticated
- **AND** they own a receipt
- **WHEN** they submit a delete request for that receipt
- **THEN** the receipt and all its expenses are deleted
- **AND** the user is redirected to the receipt list
- **AND** they see a confirmation message

#### Scenario: Delete receipt owned by another user
- **GIVEN** the user is authenticated
- **WHEN** they attempt to delete a receipt belonging to another user
- **THEN** they receive a 404 response
- **AND** the receipt is not deleted

### Requirement: Receipt-ownership-scoping
The system SHALL scope all receipt operations to the authenticated user.

#### Scenario: User sees only own receipts
- **GIVEN** two authenticated users each have receipts
- **WHEN** user A views the receipt list
- **THEN** they only see user A's receipts
- **AND** they cannot access user B's receipts via URL manipulation
