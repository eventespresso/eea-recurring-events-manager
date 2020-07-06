# Recurrence query examples

Recurrence object has connections with `RootQuery`.

## Example with `RootQuery`

```gql
query GET_RECURRENCES($first: Int, $where: EspressoRootQueryRecurrencesConnectionWhereArgs) {
	espressoRecurrences(first: $first, where: $where) {
		edges {
			node {
				id
				dbId
				cacheId
				exDates
				exRule
				gDates
				name
				rDates
				exRule
				rRule
				salesEndOffset
				salesStartOffset
			}
		}
	}
}
```

### Query variables

```json
{
	"first": 50,
	"where": {}
}
```
