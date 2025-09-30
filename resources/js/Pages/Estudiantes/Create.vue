<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Estudiante
          </h2>
          <p class="text-sm text-gray-600 mt-1">
            Registra un nuevo estudiante en el sistema
          </p>
        </div>
        <Link
          :href="route('estudiantes.index')"
          class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150"
        >
          <ArrowLeftIcon class="h-4 w-4 mr-2" />
          Volver
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="mb-6">
          <div class="bg-gray-200 rounded-full h-2">
            <div 
              class="bg-primary-600 h-2 rounded-full transition-all duration-300"
              :style="{ width: `${porcentajeCompletado}%` }"
            ></div>
          </div>
          <p class="text-sm text-gray-600 mt-2 text-center">
            Paso {{ pasoActual + 1 }} de {{ pasos.length }} - {{ porcentajeCompletado }}% completado
          </p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
          <div class="flex items-center justify-center">
            <div v-for="(step, index) in pasos" :key="index" class="flex items-center">
              <div
                :class="[
                  'flex items-center justify-center w-12 h-12 rounded-full border-2 text-sm font-medium transition-all duration-200 cursor-pointer',
                  pasoCompletado[index]
                    ? 'bg-primary-600 border-primary-600 text-white'
                    : pasoActual === index
                    ? 'border-primary-600 text-primary-600 bg-primary-50'
                    : 'border-gray-300 text-gray-500 hover:border-gray-400'
                ]"
                @click="irAPaso(index)"
              >
                <CheckIcon v-if="pasoCompletado[index]" class="h-6 w-6" />
                <span v-else class="text-lg">{{ step.icon }}</span>
              </div>
              <div v-if="index < pasos.length - 1" class="ml-4 mr-4">
                <div
                  :class="[
                    'h-1 w-16 rounded transition-all duration-200',
                    pasoCompletado[index] ? 'bg-primary-600' : 'bg-gray-300'
                  ]"
                ></div>
              </div>
            </div>
          </div>
          <div class="flex justify-center mt-4">
            <div class="text-center">
              <h3 class="text-xl font-semibold text-gray-900">{{ pasoActualData.title }}</h3>
              <p class="text-sm text-gray-500 mt-1">{{ pasoActualData.description }}</p>
            </div>
          </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
          <form @submit.prevent="submitForm" class="p-8">
            
            <!-- PASO 1: Información Personal -->
            <div v-show="pasoActual === 0" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombres -->
                <div>
                  <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombres *
                  </label>
                  <input
                    id="nombres"
                    v-model="form.nombres"
                    type="text"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.nombres }"
                  />
                  <p v-if="form.errors.nombres" class="mt-1 text-sm text-red-600">
                    {{ form.errors.nombres }}
                  </p>
                </div>

                <!-- Apellidos -->
                <div>
                  <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">
                    Apellidos *
                  </label>
                  <input
                    id="apellidos"
                    v-model="form.apellidos"
                    type="text"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.apellidos }"
                  />
                  <p v-if="form.errors.apellidos" class="mt-1 text-sm text-red-600">
                    {{ form.errors.apellidos }}
                  </p>
                </div>

                <!-- Tipo de Documento -->
                <div>
                  <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Documento *
                  </label>
                  <select
                    id="tipo_documento"
                    v-model="form.tipo_documento"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.tipo_documento }"
                  >
                    <option value="">Selecciona un tipo</option>
                    <option value="registro_civil">Registro Civil</option>
                    <option value="tarjeta_identidad">Tarjeta de Identidad</option>
                    <option value="cedula">Cédula</option>
                    <option value="pasaporte">Pasaporte</option>
                  </select>
                  <p v-if="form.errors.tipo_documento" class="mt-1 text-sm text-red-600">
                    {{ form.errors.tipo_documento }}
                  </p>
                </div>

                <!-- Número de Documento -->
                <div>
                  <label for="documento_identidad" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Documento *
                  </label>
                  <input
                    id="documento_identidad"
                    v-model="form.documento_identidad"
                    type="text"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.documento_identidad }"
                  />
                  <p v-if="form.errors.documento_identidad" class="mt-1 text-sm text-red-600">
                    {{ form.errors.documento_identidad }}
                  </p>
                </div>

                <!-- Género -->
                <div>
                  <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">
                    Género *
                  </label>
                  <select
                    id="genero"
                    v-model="form.genero"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.genero }"
                  >
                    <option value="">Selecciona género</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                  </select>
                  <p v-if="form.errors.genero" class="mt-1 text-sm text-red-600">
                    {{ form.errors.genero }}
                  </p>
                </div>

                <!-- Fecha de Nacimiento -->
                <div>
                  <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Nacimiento *
                  </label>
                  <input
                    id="fecha_nacimiento"
                    v-model="form.fecha_nacimiento"
                    type="date"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.fecha_nacimiento }"
                  />
                  <p v-if="form.errors.fecha_nacimiento" class="mt-1 text-sm text-red-600">
                    {{ form.errors.fecha_nacimiento }}
                  </p>
                </div>
              </div>

              <!-- Lugar de Nacimiento con Selects en Cascada -->
              <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Lugar de Nacimiento</h3>
                <CascadingLocationSelect 
                  v-model="birthLocation"
                  :errors="{
                    country: form.errors.birth_country_id,
                    state: form.errors.birth_state_id,
                    city: form.errors.birth_city_id
                  }"
                  @change="onBirthLocationChange"
                />
              </div>
            </div>

            <!-- PASO 2: Información de Contacto -->
            <div v-show="pasoActual === 1" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dirección -->
                <div class="md:col-span-2">
                  <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                    Dirección de Residencia *
                  </label>
                  <textarea
                    id="direccion"
                    v-model="form.direccion"
                    rows="3"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.direccion }"
                  ></textarea>
                  <p v-if="form.errors.direccion" class="mt-1 text-sm text-red-600">
                    {{ form.errors.direccion }}
                  </p>
                </div>

                <!-- Teléfono -->
                <div>
                  <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                  </label>
                  <input
                    id="telefono"
                    v-model="form.telefono"
                    type="tel"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  />
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                  </label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.email }"
                  />
                  <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                    {{ form.errors.email }}
                  </p>
                </div>
              </div>

              <!-- Contacto de Emergencia -->
              <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contacto de Emergencia</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div>
                    <label for="contacto_emergencia_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                      Nombre Completo *
                    </label>
                    <input
                      id="contacto_emergencia_nombre"
                      v-model="form.contacto_emergencia_nombre"
                      type="text"
                      required
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.contacto_emergencia_nombre }"
                    />
                    <p v-if="form.errors.contacto_emergencia_nombre" class="mt-1 text-sm text-red-600">
                      {{ form.errors.contacto_emergencia_nombre }}
                    </p>
                  </div>

                  <div>
                    <label for="contacto_emergencia_telefono" class="block text-sm font-medium text-gray-700 mb-2">
                      Teléfono *
                    </label>
                    <input
                      id="contacto_emergencia_telefono"
                      v-model="form.contacto_emergencia_telefono"
                      type="tel"
                      required
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.contacto_emergencia_telefono }"
                    />
                    <p v-if="form.errors.contacto_emergencia_telefono" class="mt-1 text-sm text-red-600">
                      {{ form.errors.contacto_emergencia_telefono }}
                    </p>
                  </div>

                  <div>
                    <label for="contacto_emergencia_relacion" class="block text-sm font-medium text-gray-700 mb-2">
                      Relación *
                    </label>
                    <input
                      id="contacto_emergencia_relacion"
                      v-model="form.contacto_emergencia_relacion"
                      type="text"
                      required
                      placeholder="Ej: Madre, Padre, Abuelo..."
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.contacto_emergencia_relacion }"
                    />
                    <p v-if="form.errors.contacto_emergencia_relacion" class="mt-1 text-sm text-red-600">
                      {{ form.errors.contacto_emergencia_relacion }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- PASO 3: Información de Padres -->
            <div v-show="pasoActual === 2" class="space-y-6">
              <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-blue-700">
                      Debe registrar al menos la información del padre o la madre. El acudiente adicional es opcional.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Información del Padre -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-2">👨</span>
                    Información del Padre
                  </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="padre_nombres" class="block text-sm font-medium text-gray-700 mb-1">
                      Nombres
                    </label>
                    <input
                      id="padre_nombres"
                      v-model="form.padre_nombres"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.padre_nombres }"
                    />
                    <p v-if="form.errors.padre_nombres" class="mt-1 text-sm text-red-600">
                      {{ form.errors.padre_nombres }}
                    </p>
                  </div>

                  <div>
                    <label for="padre_apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                      Apellidos
                    </label>
                    <input
                      id="padre_apellidos"
                      v-model="form.padre_apellidos"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.padre_apellidos }"
                    />
                    <p v-if="form.errors.padre_apellidos" class="mt-1 text-sm text-red-600">
                      {{ form.errors.padre_apellidos }}
                    </p>
                  </div>

                  <div>
                    <label for="padre_tipo_documento" class="block text-sm font-medium text-gray-700 mb-1">
                      Tipo de Documento
                    </label>
                    <select
                      id="padre_tipo_documento"
                      v-model="form.padre_tipo_documento"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    >
                      <option value="">Selecciona un tipo</option>
                      <option value="registro_civil">Registro Civil</option>
                      <option value="tarjeta_identidad">Tarjeta de Identidad</option>
                      <option value="cedula">Cédula</option>
                      <option value="pasaporte">Pasaporte</option>
                    </select>
                  </div>

                  <div>
                    <label for="padre_documento" class="block text-sm font-medium text-gray-700 mb-1">
                      Número de Documento
                    </label>
                    <input
                      id="padre_documento"
                      v-model="form.padre_documento"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.padre_documento }"
                    />
                    <p v-if="form.errors.padre_documento" class="mt-1 text-sm text-red-600">
                      {{ form.errors.padre_documento }}
                    </p>
                  </div>

                  <div>
                    <label for="padre_telefono" class="block text-sm font-medium text-gray-700 mb-1">
                      Teléfono
                    </label>
                    <input
                      id="padre_telefono"
                      v-model="form.padre_telefono"
                      type="tel"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.padre_telefono }"
                    />
                    <p v-if="form.errors.padre_telefono" class="mt-1 text-sm text-red-600">
                      {{ form.errors.padre_telefono }}
                    </p>
                  </div>

                  <div>
                    <label for="padre_email" class="block text-sm font-medium text-gray-700 mb-1">
                      Email
                    </label>
                    <input
                      id="padre_email"
                      v-model="form.padre_email"
                      type="email"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>

                  <div>
                    <label for="padre_ocupacion" class="block text-sm font-medium text-gray-700 mb-1">
                      Ocupación
                    </label>
                    <input
                      id="padre_ocupacion"
                      v-model="form.padre_ocupacion"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>

                  <div>
                    <label for="padre_lugar_trabajo" class="block text-sm font-medium text-gray-700 mb-1">
                      Lugar de Trabajo
                    </label>
                    <input
                      id="padre_lugar_trabajo"
                      v-model="form.padre_lugar_trabajo"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>
                </div>

                <div class="mt-4">
                  <label class="flex items-center">
                    <input
                      v-model="form.padre_autorizado_recoger"
                      type="checkbox"
                      class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                    />
                    <span class="ml-2 text-sm text-gray-600">Autorizado para recoger al estudiante</span>
                  </label>
                </div>
              </div>

              <!-- Información de la Madre -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-2">👩</span>
                    Información de la Madre
                  </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="madre_nombres" class="block text-sm font-medium text-gray-700 mb-1">
                      Nombres
                    </label>
                    <input
                      id="madre_nombres"
                      v-model="form.madre_nombres"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.madre_nombres }"
                    />
                    <p v-if="form.errors.madre_nombres" class="mt-1 text-sm text-red-600">
                      {{ form.errors.madre_nombres }}
                    </p>
                  </div>

                  <div>
                    <label for="madre_apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                      Apellidos
                    </label>
                    <input
                      id="madre_apellidos"
                      v-model="form.madre_apellidos"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.madre_apellidos }"
                    />
                    <p v-if="form.errors.madre_apellidos" class="mt-1 text-sm text-red-600">
                      {{ form.errors.madre_apellidos }}
                    </p>
                  </div>

                  <div>
                    <label for="madre_tipo_documento" class="block text-sm font-medium text-gray-700 mb-1">
                      Tipo de Documento
                    </label>
                    <select
                      id="madre_tipo_documento"
                      v-model="form.madre_tipo_documento"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    >
                      <option value="">Selecciona un tipo</option>
                      <option value="registro_civil">Registro Civil</option>
                      <option value="tarjeta_identidad">Tarjeta de Identidad</option>
                      <option value="cedula">Cédula</option>
                      <option value="pasaporte">Pasaporte</option>
                    </select>
                  </div>

                  <div>
                    <label for="madre_documento" class="block text-sm font-medium text-gray-700 mb-1">
                      Número de Documento
                    </label>
                    <input
                      id="madre_documento"
                      v-model="form.madre_documento"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.madre_documento }"
                    />
                    <p v-if="form.errors.madre_documento" class="mt-1 text-sm text-red-600">
                      {{ form.errors.madre_documento }}
                    </p>
                  </div>

                  <div>
                    <label for="madre_telefono" class="block text-sm font-medium text-gray-700 mb-1">
                      Teléfono
                    </label>
                    <input
                      id="madre_telefono"
                      v-model="form.madre_telefono"
                      type="tel"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.madre_telefono }"
                    />
                    <p v-if="form.errors.madre_telefono" class="mt-1 text-sm text-red-600">
                      {{ form.errors.madre_telefono }}
                    </p>
                  </div>

                  <div>
                    <label for="madre_email" class="block text-sm font-medium text-gray-700 mb-1">
                      Email
                    </label>
                    <input
                      id="madre_email"
                      v-model="form.madre_email"
                      type="email"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>

                  <div>
                    <label for="madre_ocupacion" class="block text-sm font-medium text-gray-700 mb-1">
                      Ocupación
                    </label>
                    <input
                      id="madre_ocupacion"
                      v-model="form.madre_ocupacion"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>

                  <div>
                    <label for="madre_lugar_trabajo" class="block text-sm font-medium text-gray-700 mb-1">
                      Lugar de Trabajo
                    </label>
                    <input
                      id="madre_lugar_trabajo"
                      v-model="form.madre_lugar_trabajo"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>
                </div>

                <div class="mt-4">
                  <label class="flex items-center">
                    <input
                      v-model="form.madre_autorizada_recoger"
                      type="checkbox"
                      class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                    />
                    <span class="ml-2 text-sm text-gray-600">Autorizada para recoger al estudiante</span>
                  </label>
                </div>
              </div>

              <!-- Acudiente Adicional -->
              <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-2">👤</span>
                    Acudiente Adicional (Opcional)
                  </h3>
                  <label class="flex items-center">
                    <input
                      v-model="form.tiene_acudiente_adicional"
                      type="checkbox"
                      class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                    />
                    <span class="ml-2 text-sm text-gray-600">¿Hay un acudiente adicional?</span>
                  </label>
                </div>
                
                <div v-if="form.tiene_acudiente_adicional" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="acudiente_nombres" class="block text-sm font-medium text-gray-700 mb-1">
                      Nombres *
                    </label>
                    <input
                      id="acudiente_nombres"
                      v-model="form.acudiente_nombres"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.acudiente_nombres }"
                    />
                    <p v-if="form.errors.acudiente_nombres" class="mt-1 text-sm text-red-600">
                      {{ form.errors.acudiente_nombres }}
                    </p>
                  </div>

                  <div>
                    <label for="acudiente_apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                      Apellidos *
                    </label>
                    <input
                      id="acudiente_apellidos"
                      v-model="form.acudiente_apellidos"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.acudiente_apellidos }"
                    />
                    <p v-if="form.errors.acudiente_apellidos" class="mt-1 text-sm text-red-600">
                      {{ form.errors.acudiente_apellidos }}
                    </p>
                  </div>

                  <div>
                    <label for="acudiente_tipo_documento" class="block text-sm font-medium text-gray-700 mb-1">
                      Tipo de Documento *
                    </label>
                    <select
                      id="acudiente_tipo_documento"
                      v-model="form.acudiente_tipo_documento"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    >
                      <option value="">Selecciona un tipo</option>
                      <option value="registro_civil">Registro Civil</option>
                      <option value="tarjeta_identidad">Tarjeta de Identidad</option>
                      <option value="cedula">Cédula</option>
                      <option value="pasaporte">Pasaporte</option>
                    </select>
                  </div>

                  <div>
                    <label for="acudiente_documento" class="block text-sm font-medium text-gray-700 mb-1">
                      Número de Documento *
                    </label>
                    <input
                      id="acudiente_documento"
                      v-model="form.acudiente_documento"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.acudiente_documento }"
                    />
                    <p v-if="form.errors.acudiente_documento" class="mt-1 text-sm text-red-600">
                      {{ form.errors.acudiente_documento }}
                    </p>
                  </div>

                  <div>
                    <label for="acudiente_parentesco" class="block text-sm font-medium text-gray-700 mb-1">
                      Parentesco *
                    </label>
                    <input
                      id="acudiente_parentesco"
                      v-model="form.acudiente_parentesco"
                      type="text"
                      placeholder="Ej: Abuelo, Tío, Hermano..."
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.acudiente_parentesco }"
                    />
                    <p v-if="form.errors.acudiente_parentesco" class="mt-1 text-sm text-red-600">
                      {{ form.errors.acudiente_parentesco }}
                    </p>
                  </div>

                  <div>
                    <label for="acudiente_telefono" class="block text-sm font-medium text-gray-700 mb-1">
                      Teléfono *
                    </label>
                    <input
                      id="acudiente_telefono"
                      v-model="form.acudiente_telefono"
                      type="tel"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      :class="{ 'border-red-500': form.errors.acudiente_telefono }"
                    />
                    <p v-if="form.errors.acudiente_telefono" class="mt-1 text-sm text-red-600">
                      {{ form.errors.acudiente_telefono }}
                    </p>
                  </div>

                  <div class="md:col-span-2">
                    <label for="acudiente_email" class="block text-sm font-medium text-gray-700 mb-1">
                      Email
                    </label>
                    <input
                      id="acudiente_email"
                      v-model="form.acudiente_email"
                      type="email"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- PASO 4: Información Académica -->
            <div v-show="pasoActual === 3" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grado -->
                <div>
                  <label for="grado_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Grado *
                  </label>
                  <select
                    id="grado_id"
                    v-model="form.grado_id"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.grado_id }"
                  >
                    <option value="">Selecciona un grado</option>
                    <option v-for="grado in grados" :key="grado.id" :value="grado.id">
                      {{ grado.nombre }}
                    </option>
                  </select>
                  <p v-if="form.errors.grado_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.grado_id }}
                  </p>
                </div>

                <!-- Fecha de Ingreso -->
                <div>
                  <label for="fecha_ingreso" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Ingreso *
                  </label>
                  <input
                    id="fecha_ingreso"
                    v-model="form.fecha_ingreso"
                    type="date"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.fecha_ingreso }"
                  />
                  <p v-if="form.errors.fecha_ingreso" class="mt-1 text-sm text-red-600">
                    {{ form.errors.fecha_ingreso }}
                  </p>
                </div>

                <!-- Código de Estudiante -->
                <div class="md:col-span-2">
                  <label for="codigo_estudiante" class="block text-sm font-medium text-gray-700 mb-2">
                    Código de Estudiante
                  </label>
                  <input
                    id="codigo_estudiante"
                    v-model="codigoGenerado"
                    type="text"
                    readonly
                    class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 cursor-not-allowed"
                  />
                  <p class="mt-1 text-sm text-gray-500">
                    Se genera automáticamente basado en el grado, año y documento
                  </p>
                </div>
              </div>

              <!-- Estado del Estudiante -->
              <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Estado del Estudiante</h3>
                <div class="space-y-4">
                  <div>
                    <label class="flex items-center">
                      <input
                        v-model="form.es_estudiante_nuevo"
                        type="checkbox"
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                      />
                      <span class="ml-2 text-sm text-gray-700">¿Es estudiante nuevo en la institución?</span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Antecedentes Académicos (solo si no es estudiante nuevo) -->
              <div v-if="!form.es_estudiante_nuevo" class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Antecedentes Académicos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="colegio_procedencia" class="block text-sm font-medium text-gray-700 mb-2">
                      Colegio de Procedencia
                    </label>
                    <input
                      id="colegio_procedencia"
                      v-model="form.colegio_procedencia"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>

                  <div>
                    <label for="ultimo_grado_cursado" class="block text-sm font-medium text-gray-700 mb-2">
                      Último Grado Cursado
                    </label>
                    <input
                      id="ultimo_grado_cursado"
                      v-model="form.ultimo_grado_cursado"
                      type="text"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>

                  <div>
                    <label for="ano_finalizacion" class="block text-sm font-medium text-gray-700 mb-2">
                      Año de Finalización
                    </label>
                    <input
                      id="ano_finalizacion"
                      v-model="form.ano_finalizacion"
                      type="number"
                      min="2000"
                      max="2030"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                  </div>

                  <div>
                    <label class="flex items-center pt-7">
                      <input
                        v-model="form.tiene_certificados_pendientes"
                        type="checkbox"
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                      />
                      <span class="ml-2 text-sm text-gray-700">¿Tiene certificados pendientes?</span>
                    </label>
                  </div>

                  <div class="md:col-span-2">
                    <label for="observaciones_academicas" class="block text-sm font-medium text-gray-700 mb-2">
                      Observaciones Académicas
                    </label>
                    <textarea
                      id="observaciones_academicas"
                      v-model="form.observaciones_academicas"
                      rows="3"
                      placeholder="Cualquier información adicional sobre el historial académico..."
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- PASO 5: Información Médica -->
            <div v-show="pasoActual === 4" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Sangre -->
                <div>
                  <label for="tipo_sangre" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Sangre *
                  </label>
                  <select
                    id="tipo_sangre"
                    v-model="form.tipo_sangre"
                    required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.tipo_sangre }"
                  >
                    <option value="">Selecciona tipo de sangre</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                  </select>
                  <p v-if="form.errors.tipo_sangre" class="mt-1 text-sm text-red-600">
                    {{ form.errors.tipo_sangre }}
                  </p>
                </div>

                <!-- EPS -->
                <div>
                  <label for="eps" class="block text-sm font-medium text-gray-700 mb-2">
                    EPS/Seguro de Salud *
                  </label>
                  <input
                    id="eps"
                    v-model="form.eps"
                    type="text"
                    required
                    placeholder="Ej: Sura, Nueva EPS, Sanitas..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    :class="{ 'border-red-500': form.errors.eps }"
                  />
                  <p v-if="form.errors.eps" class="mt-1 text-sm text-red-600">
                    {{ form.errors.eps }}
                  </p>
                </div>

                <!-- Número de Afiliación EPS -->
                <div class="md:col-span-2">
                  <label for="numero_afiliacion_eps" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Afiliación EPS
                  </label>
                  <input
                    id="numero_afiliacion_eps"
                    v-model="form.numero_afiliacion_eps"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  />
                </div>

                <!-- Alergias -->
                <div class="md:col-span-2">
                  <label for="alergias" class="block text-sm font-medium text-gray-700 mb-2">
                    Alergias
                  </label>
                  <textarea
                    id="alergias"
                    v-model="form.alergias"
                    rows="3"
                    placeholder="Describe cualquier alergia conocida (alimentos, medicamentos, ambientales)..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  ></textarea>
                </div>

                <!-- Medicamentos -->
                <div class="md:col-span-2">
                  <label for="medicamentos" class="block text-sm font-medium text-gray-700 mb-2">
                    Medicamentos
                  </label>
                  <textarea
                    id="medicamentos"
                    v-model="form.medicamentos"
                    rows="3"
                    placeholder="Lista medicamentos que toma regularmente, dosis y frecuencia..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  ></textarea>
                </div>

                <!-- Condiciones Médicas -->
                <div class="md:col-span-2">
                  <label for="condiciones_medicas" class="block text-sm font-medium text-gray-700 mb-2">
                    Condiciones Médicas
                  </label>
                  <textarea
                    id="condiciones_medicas"
                    v-model="form.condiciones_medicas"
                    rows="3"
                    placeholder="Describe cualquier condición médica relevante (diabetes, asma, epilepsia, etc.)..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  ></textarea>
                </div>

                <!-- Restricciones Físicas -->
                <div class="md:col-span-2">
                  <label for="restricciones_fisicas" class="block text-sm font-medium text-gray-700 mb-2">
                    Restricciones Físicas
                  </label>
                  <textarea
                    id="restricciones_fisicas"
                    v-model="form.restricciones_fisicas"
                    rows="3"
                    placeholder="Indica si tiene restricciones para educación física u otras actividades..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  ></textarea>
                </div>
              </div>

              <!-- Foto del Estudiante -->
              <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Foto del Estudiante</h3>
                <div class="flex items-start space-x-6">
                  <div class="flex-1">
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                      Subir Foto
                    </label>
                    <input
                      id="foto"
                      type="file"
                      accept="image/*"
                      @change="handleFileUpload"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                    <p class="mt-1 text-sm text-gray-500">
                      Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                    </p>
                  </div>
                  
                  <div v-if="previewFoto" class="flex-shrink-0">
                    <div class="w-24 h-24 border-2 border-gray-300 rounded-lg overflow-hidden">
                      <img
                        :src="previewFoto"
                        alt="Vista previa"
                        class="w-full h-full object-cover"
                      />
                    </div>
                    <button
                      type="button"
                      @click="eliminarFoto"
                      class="mt-2 text-sm text-red-600 hover:text-red-800"
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              </div>

              <!-- Observaciones Generales -->
              <div class="border-t pt-6">
                <div>
                  <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                    Observaciones Generales
                  </label>
                  <textarea
                    id="observaciones"
                    v-model="form.observaciones"
                    rows="4"
                    placeholder="Cualquier información adicional que considere importante..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Botones de Navegación -->
            <div class="flex justify-between pt-6 border-t">
              <button
                type="button"
                @click="pasoAnterior"
                v-show="!esPrimerPaso"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150"
              >
                <ArrowLeftIcon class="h-4 w-4 mr-2" />
                Anterior
              </button>
              
              <div class="flex space-x-3">
                <button
                  type="button"
                  @click="siguientePaso"
                  v-show="!esUltimoPaso"
                  :disabled="!puedeAvanzar"
                  class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Siguiente
                  <ArrowRightIcon class="h-4 w-4 ml-2" />
                </button>
                
                <button
                  type="submit"
                  v-show="esUltimoPaso"
                  :disabled="form.processing || !formularioCompleto"
                  class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="form.processing">Guardando...</span>
                  <span v-else>
                    <CheckIcon class="h-4 w-4 mr-2" />
                    Crear Estudiante
                  </span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Components
import CascadingLocationSelect from '@/Components/CascadingLocationSelect.vue'

// Composables
import { useFormularioEstudiante } from '@/Composables/useFormularioEstudiante.js'
import { useCodigoEstudiante } from '@/Composables/useCodigoEstudiante.js'

// Icons
import {
  ArrowLeftIcon,
  ArrowRightIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  grados: Array
})

// Composables
const {
  pasoActual,
  pasoCompletado,
  pasos,
  form,
  pasoActualData,
  esUltimoPaso,
  esPrimerPaso,
  porcentajeCompletado,
  puedeAvanzar,
  formularioCompleto,
  siguientePaso,
  pasoAnterior,
  irAPaso
} = useFormularioEstudiante()

// Estado para lugar de nacimiento
const birthLocation = ref({
  country_id: null,
  state_id: null,
  city_id: null
})

const {
  codigoGenerado,
  documento,
  grado,
  fechaIngreso
} = useCodigoEstudiante()

// Estado para foto
const previewFoto = ref(null)

// Método para manejar cambios en ubicación de nacimiento
const onBirthLocationChange = (newLocation) => {
  form.birth_country_id = newLocation.country_id
  form.birth_state_id = newLocation.state_id
  form.birth_city_id = newLocation.city_id
}

// Sincronizar valores iniciales del formulario con el componente de ubicación
watch(() => [form.birth_country_id, form.birth_state_id, form.birth_city_id], () => {
  birthLocation.value = {
    country_id: form.birth_country_id,
    state_id: form.birth_state_id,
    city_id: form.birth_city_id
  }
}, { immediate: true })

// Watchers para sincronizar con el composable de código
watch(() => form.documento_identidad, (newVal) => {
  documento.value = newVal
})

watch(() => form.grado_id, (newVal) => {
  const gradoSeleccionado = props.grados.find(g => g.id == newVal)
  if (gradoSeleccionado) {
    grado.value = gradoSeleccionado.slug || gradoSeleccionado.nombre.toLowerCase()
  }
})

watch(() => form.fecha_ingreso, (newVal) => {
  fechaIngreso.value = newVal
})

// Sincronizar código generado con el formulario
watch(codigoGenerado, (newVal) => {
  form.codigo_estudiante = newVal
})

// Métodos
const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.foto = file
    
    // Crear preview
    const reader = new FileReader()
    reader.onload = (e) => {
      previewFoto.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const eliminarFoto = () => {
  form.foto = null
  previewFoto.value = null
}

const submitForm = () => {
  if (!formularioCompleto.value) return

  form.post(route('estudiantes.store'), {
    onSuccess: () => {
      // Redirección manejada por Laravel
    },
    onError: (errors) => {
      // Ir al primer paso con errores
      for (let i = 0; i < pasos.length; i++) {
        const camposPaso = pasos[i].campos
        const tieneErrores = Object.keys(errors).some(campo => camposPaso.includes(campo))
        if (tieneErrores) {
          irAPaso(i)
          break
        }
      }
    }
  })
}
</script>