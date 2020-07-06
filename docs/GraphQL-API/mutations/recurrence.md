# EspressoRecurrence mutation examples

EspressoRecurrence object has three mutations:

-   `createEspressoRecurrence`
-   `updateEspressoRecurrence`
-   `deleteEspressoRecurrence`

## `createEspressoRecurrence`

This mutation creates a new recurrence.

```gql
mutation CREATE_RECURRENCE($input: CreateEspressoRecurrenceInput!) {
	createEspressoRecurrence(input: $input) {
		espressoRecurrence {
			id
			name
		}
	}
}
```

### Query variables for `createEspressoRecurrence`

```json
{
	"input": {
		"clientMutationId": "xyz",
		"exDates": "test-exDates",
		"exRule": "test-exRule",
		"gDates": "test-gDates",
		"name": "test-name",
		"rDates": "test-rDates",
		"rRule": "test-rRule",
		"salesEndOffset": "test-salesEndOffset",
		"salesStartOffset": "test-salesStartOffset"
	}
}
```

## `updateEspressoRecurrence`

This mutation updates an existing recurrence.

```gql
mutation UPDATE_RECURRENCE($input: UpdateEspressoRecurrenceInput!) {
	updateEspressoRecurrence(input: $input) {
		espressoRecurrence {
			id
			name
		}
	}
}
```

### Query variables for `updateEspressoRecurrence`

```json
{
	"input": {
		"clientMutationId": "xyz",
		"id": "UmVjdXJyZW5jZTox",
		"exDates": "test-exDates",
		"exRule": "test-exRule",
		"gDates": "test-gDates",
		"name": "test-name",
		"rDates": "test-rDates",
		"rRule": "test-rRule",
		"salesEndOffset": "test-salesEndOffset",
		"salesStartOffset": "test-salesStartOffset"
	}
}
```

## `deleteEspressoRecurrence`

This mutation deletes/trashes a recurrence.

```gql
mutation DELETE_RECURRENCE($input: DeleteEspressoRecurrenceInput!) {
	deleteEspressoRecurrence(input: $input) {
		espressoRecurrence {
			id
		}
	}
}
```

### Query variables for `deleteEspressoRecurrence`

```json
{
	"input": {
		"clientMutationId": "xyz",
		"id": "UmVjdXJyZW5jZTox"
	}
}
```
