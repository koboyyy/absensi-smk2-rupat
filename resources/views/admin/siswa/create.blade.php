@extends ('layouts.app', ['title' => 'Tambah Siswa'])

@section ('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Tambah Siswa</div>
        <a
            href="{{ route('admin.siswa.index') }}"
            class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900"
            >Kembali</a
        >
    </div>
    <form
        method="POST"
        action="{{ route('admin.siswa.store') }}"
        enctype="multipart/form-data"
        class="max-w-2xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">NIS</label>
                <input
                    name="nis"
                    value="{{ old('nis') }}"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                />
                @error ('nis')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Nama Siswa</label>
                <input
                    name="nama_siswa"
                    value="{{ old('nama_siswa') }}"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                />
                @error ('nama_siswa')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium"
                    >Jenis Kelamin</label
                >
                <select
                    name="jenis_kelamin"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                >
                    <option value="L" @selected (old('jenis_kelamin')==='L')
                        >L
                    </option>
                    <option value="P" @selected (old('jenis_kelamin')==='P')
                        >P
                    </option>
                </select>
                @error ('jenis_kelamin')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Kelas</label>
                <select
                    name="kelas_id"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                >
                    @foreach ($kelas as $k)
                        <option
                            value="{{ $k->kelas_id }}"
                            @selected (old('kelas_id')==$k->kelas_id)
                            >{{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error ('kelas_id')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Orang Tua</label>
                <select
                    name="ortu_id"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                >
                    <option value="">-</option>
                    @foreach ($ortu as $o)
                        <option
                            value="{{ $o->ortu_id }}"
                            @selected (old('ortu_id')==$o->ortu_id)
                            >{{ $o->nama_ortu }}
                        </option>
                    @endforeach
                </select>
                @error ('ortu_id')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Alamat</label>
            <input
                name="alamat"
                value="{{ old('alamat') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
            />
            @error ('alamat')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Foto Siswa</label>
            <div class="flex flex-col items-center gap-4 md:flex-row">
                <div id="preview-container" class="hidden">
                    <img
                        id="image-preview"
                        src="#"
                        alt="Preview"
                        class="h-24 w-24 rounded-lg object-cover border border-slate-200 dark:border-slate-800"
                    />
                </div>
                <input
                    type="file"
                    name="foto"
                    id="foto-input"
                    accept="image/*"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-900 dark:file:text-blue-400"
                />
            </div>
            <p class="mt-1 text-xs text-slate-500 italic">*Format: JPG, PNG, JPEG. Maksimal 2MB.</p>
            @error ('foto')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <button
            class="rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700"
        >
            Simpan Data Siswa
        </button>
    </form>
    <script>
        document.getElementById("foto-input").onchange = (evt) => {
            const [file] = document.getElementById("foto-input").files;
            if (file) {
                document
                    .getElementById("preview-container")
                    .classList.remove("hidden");
                document.getElementById("image-preview").src =
                    URL.createObjectURL(file);
            }
        };
    </script>
@endsection
