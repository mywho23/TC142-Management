<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $tipe_ceklis_id
 * @property string $device_grup
 * @property string $maintenance_manual
 * @property string $table_ref
 * @property string $subjek
 * @property string $action
 * @property-read \App\Models\Device|null $device
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CeklisResult> $result
 * @property-read int|null $result_count
 * @property-read \App\Models\TipeCeklis|null $tipe
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem whereDeviceGrup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem whereMaintenanceManual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem whereSubjek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem whereTableRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisItem whereTipeCeklisId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCeklisItem {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ceklis_item_id
 * @property int $device_id
 * @property string $result
 * @property string|null $notes
 * @property string $tanggal_cek
 * @property string $nama_teknisi
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Device|null $device
 * @property-read \App\Models\CeklisItem|null $item
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereCeklisItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereNamaTeknisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereTanggalCek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CeklisResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCeklisResult {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $device_name
 * @property string $device_code
 * @property string $deskripsi
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CeklisItem> $ceklisItems
 * @property-read int|null $ceklis_items_count
 * @property-read mixed $formatted_code
 * @property-read mixed $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal> $jadwal
 * @property-read int|null $jadwal_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereDeviceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereDeviceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDevice {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $device_id
 * @property string $tanggal
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string $customer
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Device|null $device
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperJadwal {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $subjek
 * @property string $tanggal_temuan
 * @property string $reporter
 * @property string|null $aksi_perbaikan
 * @property string|null $tanggal_perbaikan
 * @property int|null $executor_id
 * @property int $device_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Device|null $device
 * @property-read \App\Models\User|null $executor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereAksiPerbaikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereExecutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereReporter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereSubjek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereTanggalPerbaikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereTanggalTemuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mmi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMmi {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $judul
 * @property string $device
 * @property string $tipe
 * @property int $status
 * @property string $tanggal
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Note whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNote {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $device_id
 * @property string $chapter_code
 * @property string $chapter_name
 * @property string $chapter_group
 * @property int $order_number
 * @property int $active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Device|null $device
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereChapterCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereChapterGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereChapterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgChapter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperQtgChapter {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $device_id
 * @property int $chapter_id
 * @property int $year
 * @property string $filepath
 * @property string $result
 * @property string $note
 * @property int $uploaded_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QtgChapter> $chapter
 * @property-read int|null $chapter_count
 * @property-read \App\Models\Device|null $device
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereChapterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereFilepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereUploadedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QtgUpload whereYear($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperQtgUpload {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $device_id
 * @property string|null $date_issue
 * @property string $issue
 * @property string|null $tanggal_perbaikan
 * @property string|null $aksi_perbaikan
 * @property string $status
 * @property string $keyword
 * @property string|null $pic
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Device|null $device
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereAksiPerbaikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereDateIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereTanggalPerbaikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Record whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRecord {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $deskripsi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereNama($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRole {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property-read \App\Models\Device|null $device
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CeklisItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipeCeklis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipeCeklis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipeCeklis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipeCeklis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipeCeklis whereNama($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTipeCeklis {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $full_name
 * @property int $role_id
 * @property string $status
 * @property string $img
 * @property string|null $last_login
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mmi> $mmi
 * @property-read int|null $mmi_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Role|null $role
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

