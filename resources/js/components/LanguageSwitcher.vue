<style>
.switchLang {
  cursor:pointer;
}
</style>

<template>
  <div class="flex gap-2 switchLang">
    <button
      @click="switchLang('en')"
      :class="{ 'font-bold underline': locale === 'en' }"
    >
      EN
    </button>

    <button
      @click="switchLang('bn')"
      :class="{ 'font-bold underline': locale === 'bn' }"
    >
      বাংলা
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { setLocale } from '@/i18n'

const { locale } = useI18n()

const switchLang = async (lang) => {
    await setLocale(lang)
}
</script>
