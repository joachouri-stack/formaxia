<?php
/**
 * Formaxia — Endpoint de réception du formulaire de contact.
 *
 * Reçoit les données POST du formulaire de la home page et envoie
 * un mail à info@formaxia.fr depuis le serveur Hostinger.
 *
 * Avantages vs. POST direct vers Web3Forms côté client :
 *   - aucune clé d'API exposée dans le HTML public
 *   - quota d'envoi limité uniquement par Hostinger (pas Web3Forms)
 *   - honeypot + validations côté serveur = anti-spam
 *
 * Réponse : JSON `{success: bool, message: string}`
 */

// ─── Sécurité de base ────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// CORS : autorise uniquement formaxia.fr (et localhost en dev éventuel)
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed = ['https://formaxia.fr', 'https://www.formaxia.fr', 'http://localhost', 'http://127.0.0.1'];
if (in_array($origin, $allowed, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// ─── Récupération des champs ─────────────────────────────────────────
$field = function (string $key, int $max = 500) : string {
    $v = $_POST[$key] ?? '';
    if (!is_string($v)) return '';
    $v = trim($v);
    if (mb_strlen($v) > $max) $v = mb_substr($v, 0, $max);
    return $v;
};

$prenom  = $field('prenom', 80);
$nom     = $field('nom', 80);
$metier  = $field('metier', 200);
$taille  = $field('taille', 60);
$email   = $field('email', 200);
$tel     = $field('tel', 40);
$format  = $field('format', 100);
$periode = $field('periode', 100);
$msg     = $field('msg', 4000);

// Honeypot — champ caché que les bots remplissent mais pas les humains.
// S'il est rempli, on retourne success=true (silence) pour ne pas alerter le bot.
if (!empty($_POST['website'] ?? '') || !empty($_POST['url'] ?? '')) {
    echo json_encode(['success' => true, 'message' => 'OK']);
    exit;
}

// ─── Validations ────────────────────────────────────────────────────
$errors = [];
if ($prenom === '')                          $errors[] = 'Prénom manquant';
if ($nom === '')                             $errors[] = 'Nom manquant';
if ($metier === '')                          $errors[] = 'Métier / entreprise manquant';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide';
if ($tel === '')                             $errors[] = 'Téléphone manquant';

if ($errors) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' · ', $errors)]);
    exit;
}

// ─── Construction du mail ───────────────────────────────────────────
$destinataire = 'info@formaxia.fr';
$expediteur   = 'info@formaxia.fr';         // doit être un email du domaine Hostinger
$nomExpediteur = 'Formulaire Formaxia';     // affiché comme nom d'expéditeur

// Sujet
$sujet = sprintf('[Formaxia] Nouveau lead — %s %s (%s)', $prenom, $nom, $metier);

// Corps texte brut (les BR sont \r\n pour les MTA conservateurs)
$lignes = [
    'Nouvelle demande depuis https://formaxia.fr',
    str_repeat('─', 60),
    '',
    'Prénom         : ' . $prenom,
    'Nom            : ' . $nom,
    'Métier         : ' . $metier,
    'Nombre à former: ' . ($taille ?: '—'),
    'Email          : ' . $email,
    'Téléphone      : ' . $tel,
    'Format souhaité: ' . ($format ?: '—'),
    'Période        : ' . ($periode ?: '—'),
    '',
    'Message :',
    $msg !== '' ? $msg : '(aucun message)',
    '',
    str_repeat('─', 60),
    'IP émetteur : ' . ($_SERVER['REMOTE_ADDR'] ?? '—'),
    'User-Agent  : ' . substr($_SERVER['HTTP_USER_AGENT'] ?? '—', 0, 200),
    'Reçu le     : ' . date('Y-m-d H:i:s'),
];
$corps = implode("\r\n", $lignes);

// Headers conformes RFC 5322 (encodage UTF-8 du sujet et du nom expéditeur)
$encodedSubject = '=?UTF-8?B?' . base64_encode($sujet) . '?=';
$encodedFromName = '=?UTF-8?B?' . base64_encode($nomExpediteur) . '?=';

$headers = [
    'From: ' . $encodedFromName . ' <' . $expediteur . '>',
    'Reply-To: ' . $email,
    'X-Mailer: Formaxia-Form/1.0',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
];

// ─── Envoi ──────────────────────────────────────────────────────────
$sent = @mail($destinataire, $encodedSubject, $corps, implode("\r\n", $headers), '-f' . $expediteur);

if ($sent) {
    echo json_encode([
        'success' => true,
        'message' => 'Merci ' . $prenom . ' — on vous rappelle sous 24 heures.',
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur d\'envoi. Contactez-nous directement à info@formaxia.fr ou 07 69 01 02 02.',
    ]);
}
