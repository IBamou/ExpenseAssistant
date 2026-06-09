# AI Agent Guidelines: ExpenseAssistant (Laravel Expense Wizard)

## 1. System Context & Tech Stack
You are operating inside **ExpenseAssistant**, an automated supplier receipt tracking application built for local merchants. The tool processes raw text receipts (often written in Darija, poorly formatted, or with abbreviations) and extracts structured expense items asynchronously.

### Core Technology Stack:
- **Backend Framework:** Laravel 13 (PHP 8.4)
- **Authentication:** Laravel Breeze (Blade + Alpine.js starter kit)
- **CSS Engine:** Tailwind CSS v4 (Using `@tailwindcss/vite` plugin)
- **Asset Bundler:** Vite 8+
- **Environment Management:** Laravel Herd (Windows)
- **Code Style Fixer:** Laravel Pint
- **AI Processing Engine:** Official `laravel/ai` SDK abstracted behind an interchangeable Groq API configuration.
- **Specification Engine:** OpenSpec Framework (`openspec/specs/` directory).

---

## 2. OpenSpec Workflow & Feature Architecture
Every non-trivial implementation task **MUST** go through the OpenSpec life cycle before any code generation begins.

1. **State Isolation:** You must read existing specifications from `openspec/specs/` before writing changes.
2. **Proposal Generation:** Execute your `/openspec:proposal` commands to capture requirement deltas.
3. **Structured Outputs:** Ensure changes are tracked under `openspec/changes/` containing the exact `proposal.md`, `design.md`, and broken down `tasks.md` contracts.

---

## 3. Core Architectural Guardrails
Follow these architectural rules strictly. Do not deviate under any circumstance:


## 4. Expected AI Data Extraction Schema Contract
When interacting with the `laravel/ai` SDK, you must guarantee that the structured JSON engine outputs without crashing `json_decode`


---

## 5. Development & Verification Commands
You have full terminal capabilities. Use these commands to execute code styling and run automated tests:
- **Database Migrations:** `php artisan migrate`
- **Asset Compilation:** `npm run dev` (local watcher) or `npm run build` (production compiler)
- **Code Linting/Formatting:** `./vendor/bin/pint`
- **Running Tests:** `php artisan test`

---

## 6. Definition of Done (DoD)
Before submitting code changes for review, ensure you fulfill this validation contract:
1. **Compilation Check:** Run `npm run build` to ensure assets compile seamlessly without asset generation errors.
2. **Format Adherence:** Run `./vendor/bin/pint` to cleanly align modified source code files with project styling parameters.
3. **Pest Testing:** Execute `php artisan test`. Ensure you implement fast, deterministic Pest unit tests mocking the extraction cycle via the `laravel/ai` SDK fake facade layer (avoid running actual, slow Groq API calls during testing phases).
4. **Git Commits:** Commit code following Conventional Commit formats while explicitly stating AI orchestration (e.g., `feat(ai-extraction): [AI Assist] implement custom background receipt tracking job`).
