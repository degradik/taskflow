<script lang="ts" setup>
import {v4 as uuid} from 'uuid'
import {account} from '../lib/appwrite'

definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Login | CRM System'
})

const emailRef = ref('')
const passwordRef = ref('')
const nameRef = ref('')

const isLoadingStore = useIsLoadingStore()
const authStore = useAuthStore()
const router = useRouter()

const login = async () => {
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
  nameRef.value = ''

  await router.push('/')
  isLoadingStore.set(false)
}

const register = async () => {
  await account.create(uuid(), emailRef.value, passwordRef.value, nameRef.value)
  await login()
}
</script>

<template>
  <Card class="mx-auto max-w-sm mt-[7em]">
    <CardHeader>
      <CardTitle class="flex justify-between items-center">
        <h1 class="text-xl">Регистрация</h1>
        <UtilsTheme />
      </CardTitle>
      <CardDescription class="mt-2">
        Заполните поля для создания аккаунта
      </CardDescription>
    </CardHeader>
    <CardContent>
      <div class="grid gap-4">
        <div class="grid gap-2">
          <Label for="first-name">Имя</Label>
          <Input
            v-model="nameRef"
            id="first-name"
            placeholder="Иван"
            required
          />
        </div>

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
          <Label for="password">Пароль</Label>
          <Input v-model="passwordRef" id="password" type="password" required />
        </div>
        <Button @click="register" type="submit" class="w-full">
          Создать аккаунт
        </Button>
      </div>
      <div class="mt-4 text-center text-sm">
        Уже есть аккаунт?
        <NuxtLink href="/sign-in" class="underline"> Войти </NuxtLink>
      </div>
    </CardContent>
  </Card>
</template>
