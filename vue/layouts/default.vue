<script setup lang="ts">
import {account} from '~/lib/appwrite'
import {useAuthStore, useIsLoadingStore} from '~/store/auth.store'

const isLoadingStore = useIsLoadingStore()
const store = useAuthStore()
const router = useRouter()

onMounted(async () => {
  try {
    const user = await account.get()
    if (user) store.set(user)
  } catch (error) {
    router.push('/sign-in')
  } finally {
    isLoadingStore.set(false)
  }
})
</script>

<template>
  <UtilsLoader v-if="isLoadingStore.isLoading" />
  <div v-else>
    <SidebarProvider>
      <AppSidebar />
      <SidebarInset>
        <AppHeader />
        <main>
          <slot />
        </main>
      </SidebarInset>
    </SidebarProvider>
  </div>
</template>
