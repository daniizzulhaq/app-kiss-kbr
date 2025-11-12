<!-- Role -->
<div class="mt-4">
    <x-input-label for="role" :value="__('Role')" />
    <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="kelompok">Kelompok</option>
        <option value="bpdas">BPDAS</option>
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>