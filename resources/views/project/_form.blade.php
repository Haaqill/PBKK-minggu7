<form
    method="POST"
    action="{{ isset($project) ? route('project.update', $project) : route('project.store') }}"
>
    @csrf
    @isset($project)
        @method('PUT')
    @endisset

    <div class="mb-3">
        <label for="judul" class="form-label">Judul Proyek</label>
        <input
            id="judul"
            name="judul"
            type="text"
            class="form-control"
            value="{{ old('judul', $project->judul ?? '') }}"
            maxlength="255"
            required
        >
        @error('judul')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea
            id="deskripsi"
            name="deskripsi"
            class="form-control"
            rows="5"
        >{{ old('deskripsi', $project->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="tema_agent" class="form-label">Tema Agent</label>
        <input
            id="tema_agent"
            name="tema_agent"
            type="text"
            class="form-control"
            value="{{ old('tema_agent', $project->tema_agent ?? 'Ollama') }}"
            maxlength="100"
            required
        >
        @error('tema_agent')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="api_key_secure" class="form-label">API Key</label>
        <input
            id="api_key_secure"
            name="api_key_secure"
            type="password"
            class="form-control"
            autocomplete="new-password"
        >
        <div class="form-text">
            @isset($project)
                Kosongkan untuk mempertahankan API key saat ini.
            @else
                Opsional. API key akan disimpan dalam bentuk terenkripsi.
            @endisset
        </div>
        @error('api_key_secure')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        {{ isset($project) ? 'Simpan Perubahan' : 'Simpan Proyek' }}
    </button>
    <a href="{{ route('project.index') }}" class="btn btn-link">Batal</a>
</form>
