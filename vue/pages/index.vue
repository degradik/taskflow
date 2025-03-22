<script setup lang="ts">
import type {IColumn, ICard} from '~/components/kanban/kanban.types'
import {useKanbanQuery} from '~/components/kanban/useKanbanQuery'
import dayjs from 'dayjs'
import type {EnumStatus} from '~/types/deals.types'
import {useMutation} from '@tanstack/vue-query'
import {DB} from '~/lib/appwrite'
import {COLLECTION_DEALS, DB_ID} from '~/app.constants'

const dragCardRef = ref<ICard | null>(null)
const sourceColumnRef = ref<IColumn | null>(null)

const {data, isLoading, refetch} = useKanbanQuery()

type TypeMutationVariables = {
  docId: string
  status?: EnumStatus
}

const {mutate} = useMutation({
  mutationKey: ['move card'],
  mutationFn: ({docId, status}: TypeMutationVariables) =>
    DB.updateDocument(DB_ID, COLLECTION_DEALS, docId, {
      status
    }),
  onSuccess: () => {
    refetch()
  }
})

function handleDragStart(card: ICard, column: IColumn) {
  dragCardRef.value = card
  sourceColumnRef.value = column
}

function handleDragOver(event: DragEvent) {
  event.preventDefault()
}

function handleDrop(targetColumn: IColumn) {
  if (dragCardRef.value && sourceColumnRef.value) {
    mutate({docId: dragCardRef.value.id, status: targetColumn.id})
  }
}
</script>

<template>
  <div class="max-w-7xl mx-auto">
    <h1 class="text-2xl">Kanban Board</h1>
    <div v-if="isLoading">Loading...</div>
    <div v-else class="grid md:grid-cols-5 gap-16">
      <div
        v-for="(column, index) in data"
        :key="column.id"
        @dragover="handleDragOver"
        @drop="() => handleDrop(column)"
        class="min-h-screen"
      >
        <div class="rounded bg-slate-700 py-1 px-5 mb-2 text-center">
          {{ column.name }}
        </div>

        <div>
          <KanbanCreateDeal :refetch="refetch" :status="column.id" />
          <Card
            v-for="card in column.items"
            :key="card.id"
            class="mb-4 dark:bg-slate-200 dark:text-slate-950 bg-slate-950 text-slate-200"
            draggable="true"
            @dragstart="() => handleDragStart(card, column)"
          >
            <CardHeader role="button"
              >{{ card.name }}
              <CardDescription>{{ card.price }}</CardDescription>
            </CardHeader>
            <CardContent>Компания{{ card.companyName }}</CardContent>
            <CardFooter>{{
              dayjs(card.$createdAt).format('DD MMMM YYYY')
            }}</CardFooter>
          </Card>
        </div>
      </div>
    </div>
  </div>
</template>
