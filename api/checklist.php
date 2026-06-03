<?php
/**
 * Formaxia — Endpoint de capture lead magnet "Checklist 10 tâches admin".
 *
 * Reçoit prénom + email + (optionnel) métier.
 * Envoie 2 mails :
 *   1. À l'inscrit : le lien vers la checklist + le rappel du programme.
 *   2. À info@formaxia.fr : alerte qu'un nouveau lead a téléchargé la checklist.
 *
 * Réponse JSON `{success: bool, message: string}`.
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

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

$field = function (string $key, int $max = 200) : string {
    $v = $_POST[$key] ?? '';
    if (!is_string($v)) return '';
    $v = trim($v);
    if (mb_strlen($v) > $max) $v = mb_substr($v, 0, $max);
    return $v;
};

$prenom = $field('prenom', 80);
$email  = $field('email', 200);
$metier = $field('metier', 200);

// Honeypot — bot remplit "website", on simule un succès silencieux
if (!empty($_POST['website'] ?? '')) {
    echo json_encode(['success' => true, 'message' => 'OK']);
    exit;
}

$errors = [];
if ($prenom === '') $errors[] = 'Prénom manquant';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide';
if ($errors) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' · ', $errors)]);
    exit;
}

$expediteur    = 'info@formaxia.fr';
$nomExpediteur = 'Johane Achouri · Formaxia';
$gestionMail   = 'info@formaxia.fr';
$lienChecklist = 'https://formaxia.fr/checklist/';

// ─── Mail 1 : pour l'inscrit ────────────────────────────────────────
$sujet1 = '[Formaxia] Votre checklist : 10 tâches à automatiser avec l\'IA';
$body1 = <<<TXT
Bonjour {$prenom},

Merci d'avoir téléchargé la checklist Formaxia.

Voici le lien pour la consulter (et l'imprimer si vous voulez la cocher au stylo cette semaine) :

→ {$lienChecklist}

J'ai compilé dans ce document les 10 tâches administratives qui font perdre le plus de temps aux artisans du BTP — et le gain de temps moyen quand on les automatise avec l'IA. Total : environ 10 heures par semaine.

Une fois que vous aurez fait votre propre audit (combien de temps VOUS y consacrez réellement chaque semaine), si l'envie vous prend d'aller plus loin :

→ Le programme complet : https://formaxia.fr/programme/
→ Le financement Constructys (0 € pour vous) : https://formaxia.fr/financement/

À très vite,
Johane Achouri
Fondateur Formaxia · Formateur certifié RS6776
07 69 01 02 02 · info@formaxia.fr

P.S. : si vous avez la moindre question sur l'IA dans le bâtiment, répondez à ce mail — c'est moi qui lis et qui réponds.
TXT;

$encodedSubject1 = '=?UTF-8?B?' . base64_encode($sujet1) . '?=';
$encodedFromName = '=?UTF-8?B?' . base64_encode($nomExpediteur) . '?=';
$headers1 = [
    'From: ' . $encodedFromName . ' <' . $expediteur . '>',
    'Reply-To: ' . $expediteur,
    'X-Mailer: Formaxia-LeadMagnet/1.0',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
];
$sent1 = @mail($email, $encodedSubject1, $body1, implode("\r\n", $headers1), '-f' . $expediteur);

// ─── Mail 2 : notification interne ──────────────────────────────────
$sujet2 = sprintf('[Formaxia · Lead] Checklist téléchargée — %s', $prenom);
$body2 = <<<TXT
Nouveau lead via le formulaire Checklist :

Prénom  : {$prenom}
Email   : {$email}
Métier  : {$metier}

Mail automatique envoyé à l'inscrit avec le lien vers la checklist.
À toi de prendre le relais sur les 3 emails de la séquence si tu veux les envoyer.

IP émetteur : {$_SERVER['REMOTE_ADDR']}
Reçu le     : %s
TXT;
$body2 = sprintf($body2, date('Y-m-d H:i:s'));

$encodedSubject2 = '=?UTF-8?B?' . base64_encode($sujet2) . '?=';
$headers2 = [
    'From: ' . $encodedFromName . ' <' . $expediteur . '>',
    'Reply-To: ' . $email,
    'X-Mailer: Formaxia-LeadMagnet/1.0',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
];
@mail($gestionMail, $encodedSubject2, $body2, implode("\r\n", $headers2), '-f' . $expediteur);

if ($sent1) {
    echo json_encode([
        'success' => true,
        'message' => 'Checklist envoyée dans votre boîte mail ' . $email . '.',
        'redirect' => '/checklist/',
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur d\'envoi. Accédez directement à la checklist : ' . $lienChecklist,
    ]);
}
