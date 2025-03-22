<script lang="ts" setup>
import {Icon} from '@iconify/vue'
import { account } from '~/lib/appwrite'

const store = useAuthStore()
const isLoadingStore = useIsLoadingStore()
const router = useRouter()

const logout = async () => {
  isLoadingStore.set(true)
  await account.deleteSession('current')
  store.clear()
  await router.push('/sign-in')
  isLoadingStore.set(false)
}
</script>

<template>
  <header class="flex h-16 items-center justify-between border-b">
    <div class="flex items-center px-3">
      <SidebarTrigger />
    </div>
    <div class="flex items-center gap-6 px-4">
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="outline">
            <Icon
              icon="radix-icons:person"
              class="h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all"
            />

            <span class="sr-only">Toggle theme</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent v-if="store.isAuth">
          <DropdownMenuLabel>My Account</DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem @click="logout">Выйти</DropdownMenuItem>
        </DropdownMenuContent>
        <DropdownMenuContent v-else>
          <DropdownMenuItem
            ><NuxtLink class="w-full" href="/sign-up"
              >Регистрация</NuxtLink
            ></DropdownMenuItem
          >
          <DropdownMenuItem
            ><NuxtLink class="w-full" href="/sign-in"
              >Вход</NuxtLink
            ></DropdownMenuItem
          >
        </DropdownMenuContent>
      </DropdownMenu>
      <UtilsTheme />
    </div>
  </header>
</template>
