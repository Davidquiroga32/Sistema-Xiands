@props(['name', 'id' => null, 'value' => '', 'required' => false, 'placeholder' => '$ 0', 'label' => 'Valor'])

@php
    $inputId = $id ?? $name;
    $rawValue = old($name, $value);
    $displayValue = $rawValue ? number_format((float) $rawValue, 0, ',', '.') : '';
@endphp

<div class="field-dark"
    x-data='{
        raw: @json($rawValue ? (float) $rawValue : ""),
        display: @json($displayValue),

        formatLive(val) {
            if (!val) return "";
            const digits = val.replace(/\D/g, "");
            if (!digits) return "";
            return parseInt(digits).toLocaleString("es-CO", { maximumFractionDigits: 0 });
        },

        handleInput(el) {
            const pos = el.selectionStart;
            const prev = this.display;
            this.raw = parseInt(this.display.replace(/\D/g, "")) || "";
            this.display = this.formatLive(this.display);
            const shift = this.display.length - prev.length;
            this.$nextTick(() => el.setSelectionRange(pos + shift, pos + shift));
        },

        setValue(val) {
            this.raw = parseInt(val) || "";
            this.display = this.raw ? parseInt(this.raw).toLocaleString("es-CO", { maximumFractionDigits: 0 }) : "";
        }
    }'
    x-init='window.addEventListener("set-money-{{ $inputId }}", (e) => setValue(e.detail))'
>
    <label class="label-dark" for="{{ $inputId }}_display">{{ $label }}</label>
    <input
        type="text"
        id="{{ $inputId }}_display"
        class="input-dark"
        inputmode="numeric"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        style="font-variant-numeric:tabular-nums;"
        x-model="display"
        @input="handleInput($el)"
    >
    <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" :value="raw">
    @error($name)
        <p style="color:#f87171;font-size:0.7rem;margin-top:0.4rem;">{{ $message }}</p>
    @enderror
</div>
