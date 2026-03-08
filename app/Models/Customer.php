<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'etablissement_id',
        'type',
        'civilite',
        'prenom',
        'nom',
        'email',
        'telephone',
        'telephone_secondaire',
        'entreprise_nom',
        'siret',
        'no_tva',
        'adresse',
        'complement_adresse',
        'code_postal',
        'ville',
        'pays',
        'mode_reglement_prefere',
        'delai_paiement_jours',
        'notes',
        'total_commandes',
        'chiffre_affaires_total',
        'solde_compte'
    ];

    protected $casts = [
        'total_commandes' => 'integer',
        'chiffre_affaires_total' => 'decimal:2',
        'solde_compte' => 'decimal:2',
        'delai_paiement_jours' => 'integer'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function contacts()
    {
        return $this->hasMany(ContactClient::class, 'client_id');
    }

    public function projets()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function factures()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function devis()
    {
        return $this->hasMany(Quote::class, 'client_id');
    }

    public function paiements()
    {
        return $this->hasMany(Payment::class, 'client_id');
    }

    public function contrats()
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    public function getNomCompletAttribute()
    {
        if ($this->type === 'entreprise') {
            return $this->entreprise_nom;
        }
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getAdresseCompleteAttribute()
    {
        $parts = [];
        if ($this->adresse) $parts[] = $this->adresse;
        if ($this->complement_adresse) $parts[] = $this->complement_adresse;
        $ville = trim($this->code_postal . ' ' . $this->ville);
        if ($ville) $parts[] = $ville;
        if ($this->pays) $parts[] = $this->pays;
        
        return implode("\n", $parts);
    }

    public function updateChiffreAffaires()
    {
        $this->chiffre_affaires_total = $this->factures()
            ->where('status', 'payee')
            ->sum('total');
        $this->save();
    }
}