<!-- Memuat CSS untuk jsTree -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Group Section -->
            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Group</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group">
                            <?php foreach ($level as $value): ?>
                                <button type="button" 
                                        class="list-group-item list-group-item-action" 
                                        onClick="loadMenu('<?= htmlspecialchars($value->name, ENT_QUOTES, 'UTF-8') ?>')">
                                    <?= htmlspecialchars($value->name, ENT_QUOTES, 'UTF-8') ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Section -->
            <div class="col-md-8">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Menu</h3>
                    </div>
                    <div class="card-body p-3">
                        <div id="loading" class="loading text-center" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Memuat menu...
                        </div>
                        <!-- Div untuk menampilkan JS Tree -->
                        <div id="jstree_demo_div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Memuat JS untuk jsTree -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
<script>
// Variabel global untuk menyimpan level pengguna yang sedang dipilih
let currentRole = '';

async function loadMenu(levelid) {
    $('#loading').show(); // Tampilkan loader saat proses berjalan
    currentRole = levelid; // Simpan role pengguna yang sedang dipilih
    try {
        const response = await fetch(`<?= site_url('admin/menu/getMenuByLevel') ?>?levelid=${encodeURIComponent(levelid)}`);
        
        if (!response.ok) {
            throw new Error(`Gagal memuat menu. Status HTTP: ${response.status}`);
        }

        const result = await response.json();

        if (result.data && result.data.length > 0) {
            initializeJSTree(result.data);
        } else {
            alert("Menu tidak ditemukan untuk grup ini.");
            $('#jstree_demo_div').jstree("destroy").empty(); // Kosongkan JSTree
        }
    } catch (error) {
        console.error("Error loading menu:", error);
        alert("Terjadi kesalahan saat memuat menu. Silakan coba lagi.");
    } finally {
        $('#loading').hide(); // Sembunyikan loader
    }
}

// Fungsi untuk inisialisasi JSTree dengan data menu
function initializeJSTree(menuData) {
    $('#jstree_demo_div').jstree("destroy").empty(); // Bersihkan JSTree sebelumnya

    $('#jstree_demo_div').jstree({
        core: {
            data: menuData.map(item => ({
                id: item.id,
                text: item.text,
                icon: item.icon,
                parent: item.parent === "0" ? "#" : item.parent,
                state: { selected: item.selected === "true" }
            })),
            themes: {
                name: 'default',
                dots: false,
                icons: true
            }
        },
        plugins: ["checkbox"] // Aktifkan plugin checkbox
    }).on("changed.jstree", handleTreeChange); // Tambahkan listener perubahan checkbox
}

// Fungsi untuk menangani perubahan checkbox pada JSTree
async function handleTreeChange(e, data) {
    const updates = [];
    const nodes = data.node ? [data.node] : [];

    nodes.forEach(node => {
        const isChecked = data.action === "select_node";
        updates.push({
            idmenu: node.id,
            action: isChecked ? "add" : "remove"
        });
    });

    if (updates.length > 0) {
        try {
            const response = await fetch("<?= site_url('admin/menu/updateMenuStatus') ?>", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    updates: updates,
                    role: currentRole
                })
            });

            const result = await response.json();

            if (result.status === "success") {
                alert("Status menu berhasil diperbarui.");
            } else {
                alert("Gagal memperbarui status menu: " + (result.message || "Unknown error."));
            }
        } catch (error) {
            console.error("Error updating menu:", error);
            alert("Terjadi kesalahan saat memperbarui status menu. Silakan coba lagi.");
        }
    }
}
</script>

