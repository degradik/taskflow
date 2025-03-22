<script lang="ts" setup>
import {account} from '../lib/appwrite'

definePageMeta({
  layout: false
})

const emailRef = ref('')
const passwordRef = ref('')

const isLoadingStore = useIsLoadingStore()
const authStore = useAuthStore()
const router = useRouter()

const signin = async () => {
  isLoadingStore.set(true)
  await account.createEmailPasswordSession(emailRef.value, passwordRef.value)
  const response = await account.get()
  if (response) {
    authStore.set({
      email: response.email,
      name: response.name,
      status: response.status
    })
  }

  emailRef.value = ''
  passwordRef.value = ''

  await router.push('/')
  isLoadingStore.set(false)
}

</script>

<template>
  <Card class="mx-auto max-w-sm mt-[11em]">
    <CardHeader>
      <CardTitle class="flex justify-between items-center">
        <h1 class="text-xl">Вход</h1>
        <UtilsTheme />
      </CardTitle>
      <CardDescription>
        Введите email чтобы войти в свой аккаунт
      </CardDescription>
    </CardHeader>
    <CardContent>
      <div class="grid gap-4">
        <div class="grid gap-2">
          <Label for="email">Email</Label>
          <Input
            v-model="emailRef"
            id="email"
            type="email"
            placeholder="example@example.com"
            required
          />
        </div>
        <div class="grid gap-2">
          <div class="flex items-center">
            <Label for="password">Пароль</Label>
            <NuxtLink href="#" class="ml-auto inline-block text-sm underline">
              Забыли пароль?
            </NuxtLink>
          </div>
          <Input v-model="passwordRef" id="password" type="password" required />
        </div>
        <Button @click="signin" type="submit" class="w-full"> Войти </Button>
      </div>
      <div class="mt-4 text-center text-sm">
        Нет аккаунта?
        <NuxtLink href="/sign-up" class="underline"> Регистрация </NuxtLink>
      </div>
    </CardContent>
  </Card>
</template>
