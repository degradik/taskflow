import {useQuery} from '@tanstack/vue-query'
import {COLLECTION_DEALS, DB_ID} from '~/app.constants'
import type {IDeal} from '~/types/deals.types'
import {KANBAN_DATA} from './kanban.data'
import type {IColumn} from './kanban.types'
import {DB} from '~/lib/appwrite'

export function useKanbanQuery() {
  return useQuery({
    queryKey: ['deals'],
    queryFn: () => DB.listDocuments(DB_ID, COLLECTION_DEALS),
    select(data) {
      const newBoard: IColumn[] = KANBAN_DATA.map(column => ({
        ...column,
        items: []
      }))

      const deals = data.documents as unknown as IDeal[]

      console.log(deals, newBoard)

      for (const deal of deals) {
        const column = newBoard.find(col => col.id === deal.status)
        console.log(column)
        if (column) {
          column.items.push({
            $createdAt: deal.$createdAt,
            id: deal.$id,
            name: deal.name,
            price: deal.price,
            companyName: deal.customer.name,
            status: column.name
          })
        }
      }

      console.log(newBoard)

      return newBoard
    }
  })
}
