@props([
    'name' => 'email-modal',
    'entityLabel' => 'registro',
    'maxLength' => 255,
])

<x-modal :name="$name" maxWidth="2xl" focusable>

    <div class="p-6">
        <p class="text-center font-bold text-lg py-2">Seleccione quien se notificara del evento:</p>
        <div class="flex flex-col md:flex-row items-stretch gap-4 py-2">
            <div class="flex-1 flex">
                <label for="checkbox-1" class="flex flex-1 space-x-2.5 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                    <input id="checkbox-1" x-model="flags.sendCta" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 mt-5 ms-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
                    <div class="py-4 pe-4">
                        <p class="select-none w-full text-sm font-medium text-heading">Confimar envio a CTA</p>
                        <p class="select-none text-sm text-body">Se confirma el envio automaticamente, si hay notas para CTA.</p>
                    </div>
                </label>
            </div>
            <div class="flex-1 flex">
                <label for="checkbox-2" class="flex flex-1 space-x-2.5 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                    <input id="checkbox-2" x-model="flags.sendservicios" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 mt-5 ms-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
                    <div class="py-4 pe-4">
                        <p class="select-none w-full text-sm font-medium text-heading">Confimar envio a Servicios Gnerales</p>
                        <p class="select-none text-sm text-body">Se confirma el envio automaticamente, si hay notas para Servicios Gnerales.</p>
                    </div>
                </label>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-stretch gap-4 py-2">
            <div class="flex-1 flex">
                <label for="difusion_check" class="flex flex-1 space-x-2.5 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                    <input id="difusion_check" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 mt-5 ms-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
                    <div class="py-4 pe-4">
                        <p class="select-none w-full text-sm font-medium text-heading">Confimar envio a Difusion</p>
                        <p class="select-none text-sm text-body">Confirme el check para el envio de un correo a difusion.</p>
                    </div>
                </label>
            </div>
            <div class="flex-1 flex">
                <label for="organnizador_check" class="flex flex-1 space-x-2.5 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                    <input checked id="organnizador_check" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 mt-5 ms-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
                    <div class="py-4 pe-4">
                        <p class="select-none w-full text-sm font-medium text-heading">Confirmar envio al Organizador del evento</p>
                        <p class="select-none text-sm text-body">Confirme el check para el envio a el Organizador del evento.</p>
                    </div>
                </label>
            </div>
        </div>
    </div>
</x-modal>
