# Séquence email Formaxia — 3 emails post-checklist

> **À envoyer manuellement** (ou via Brevo / Mailerlite / Mailchimp) après la réception de la checklist par l'inscrit.
>
> Le mail #0 (l'envoi de la checklist) est déjà automatisé par `/api/checklist.php`.
> Les 3 mails ci-dessous sont à programmer J+1, J+3, J+7.

---

## 📧 EMAIL 1 — J+1 · "Avez-vous testé ?"

**Sujet :** `Vous avez réussi à automatiser une seule tâche ?`

```
Bonjour {prenom},

Hier vous avez téléchargé la checklist Formaxia.

Petite question : avez-vous eu le temps d'attaquer ne serait-ce
qu'une seule des 10 tâches ?

Si oui — laquelle ? Et comment ça s'est passé ?
Si non — c'est normal. La plupart des artisans qui téléchargent
la checklist disent "je m'y mettrai cette semaine"... et trois
mois plus tard, ils l'ont toujours pas ouverte.

C'est pas un reproche. C'est la réalité du métier.

C'est exactement pour ça que la formation Formaxia existe : on
ne se contente pas de vous donner une liste. On vous fait
pratiquer pendant 2 jours, on construit ENSEMBLE vos workflows
sur VOTRE poste, avec VOS clients, sur VOS outils.

Et c'est financé à 100% par Constructys. Donc le seul coût pour
vous, c'est 2 jours de votre temps. Le retour : 10 heures par
semaine pour le reste de votre vie professionnelle.

Si l'idée vous parle, on peut en discuter 15 minutes au téléphone
sans engagement : 07 69 01 02 02 (c'est Johane qui décroche).

À très vite,
Johane Achouri
Fondateur Formaxia
```

---

## 📧 EMAIL 2 — J+3 · "L'histoire de Marc"

**Sujet :** `Marc passait 3h sur un devis. Aujourd'hui, c'est 10 minutes.`

```
Bonjour {prenom},

Je voulais vous partager l'histoire de Marc.

Marc, c'est un plombier indépendant à Lyon. Il fait des belles
installations, ses clients l'aiment bien, mais il signait pas
assez de devis. Pourquoi ?

Il les envoyait trois jours après le rendez-vous. Le temps de
les rédiger le soir, après les journées. Et trois jours, dans
ce métier, c'est souvent trop tard : le client a déjà choisi
quelqu'un d'autre.

Marc a fait la formation Formaxia en mars. Aujourd'hui, ses
devis partent dans la journée. Résultat brut : +2 clients
signés par mois en moyenne. À 4 000 € de chantier moyen,
faites le calcul.

Ce qui me touche le plus dans son retour ? Il m'a dit :
« Maintenant je suis avec mes gamins le soir, j'ai plus besoin
de bosser jusqu'à 22h pour les devis. »

C'est pour ça que je fais ce métier.

Si vous voulez la même chose — un retour de votre vie de famille
+ plus de devis signés — Constructys finance la formation à 100%.
Vous mettez juste vos 2 jours et votre énergie.

Pour discuter : 07 69 01 02 02
Ou répondez à ce mail.

Johane Achouri
Fondateur Formaxia

P.S. : Marc a accepté qu'on cite son retour sur le site —
vous le verrez en page d'accueil avec sa photo.
```

---

## 📧 EMAIL 3 — J+7 · "Garantie + appel à l'action"

**Sujet :** `Si ça marche pas, je viens vous re-former. Gratuitement.`

```
Bonjour {prenom},

Une semaine s'est passée depuis la checklist.

Soit vous avez testé et vous voyez l'intérêt — soit vous avez pas
encore eu le temps. Dans les deux cas, je veux clarifier UNE
chose pour qu'on ne se quitte pas sur un doute.

**Engagement résultat à J+7.**

Si vous suivez la formation Formaxia et qu'une semaine plus tard
vous n'avez PAS récupéré de temps sur votre administratif,
je reviens. Gratuitement. Session de réajustement individuelle,
en visio ou en présentiel selon votre préférence.

C'est noir sur blanc. Pas de petites lignes.

Pourquoi je peux promettre ça ? Parce qu'on a déjà formé des
artisans plus sceptiques que vous, et qu'aucun n'a eu besoin
de cette session de rattrapage. La méthode marche. Point.

Si vous voulez avancer maintenant :

→ Programme complet : https://formaxia.fr/programme/
→ Financement Constructys (0€) : https://formaxia.fr/financement/
→ Réserver un appel de 15 min : 07 69 01 02 02

Si vous voulez attendre — pas de souci. Pas de relance
commerciale après ce mail. Je serai là quand vous serez prêt(e).

Bon chantier,
Johane Achouri
Fondateur Formaxia

P.S. : ce mail clôt la séquence. Si vous voulez rester au courant
des prochaines sessions, du blog, et des nouveaux contenus IA-BTP,
restez abonné (pas d'action à faire). Sinon, désabonnement en un
clic : répondez "STOP" à ce mail.
```

---

## 🔧 Comment automatiser cette séquence

### Option A — À la main
Programmez 3 rappels dans votre agenda chaque fois qu'un lead arrive.
Très acceptable jusqu'à ~20 leads/mois.

### Option B — Brevo (ex-Sendinblue) — gratuit jusqu'à 300 emails/jour
1. Compte sur https://www.brevo.com
2. Créer une liste "Checklist leads"
3. Créer un scénario d'automation avec 3 étapes (J+1, J+3, J+7)
4. Modifier `api/checklist.php` pour pousser le lead dans Brevo via leur API
5. Coût : 0 € jusqu'à 300 envois/jour

### Option C — Mailerlite (gratuit jusqu'à 1000 abonnés)
Pareil que Brevo, autre fournisseur. Interface plus simple selon certains.

### Option D — En sortir vraiment : Lemlist / Smartlead
Pour quand vous aurez 100+ leads/mois et voudrez personnaliser.

---

## 📊 Métriques à suivre

| Métrique | Cible mois 1 | Cible mois 3 |
|---|---|---|
| Taux de capture checklist (visiteurs → leads) | 3-5% | 5-8% |
| Taux d'ouverture email J+1 | > 40% | > 50% |
| Taux de clic mail J+3 (vers /programme/) | > 8% | > 12% |
| Conversion leads → inscrits formation | 2-3% | 5-7% |

Avec 100 visites/jour et 5% de capture, on est à 150 leads/mois.
Avec 5% de conversion, ça fait 7-8 inscriptions/mois.
À 546 € (financés Constructys), ça équilibre largement.
