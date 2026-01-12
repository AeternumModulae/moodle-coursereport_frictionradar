/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

<?php
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Friction Radar';
$string['plugindescription'] = 'Frühe Hinweise auf Lernreibung auf Basis aggregierter Kursaktivitäten.';
$string['navitem'] = 'Friction Radar';
$string['page_title'] = 'Lernreibung im Kurs';
$string['page_subtitle'] = 'Aggregierte kursbezogene Signale (rollierendes 6-Wochen-Fenster).';
$string['overall_score'] = 'Gesamtwert Lernreibung';
$string['generated_at'] = 'Erzeugt';
$string['window'] = 'Fenster';
$string['days'] = 'Tage';
$string['no_data'] = 'Noch nicht genug Daten. Werte erscheinen nach der nächtlichen Cache-Erzeugung.';
$string['warmcache_now'] = 'Jetzt Werte erzeugen';
$string['warmcache_done'] = 'Cache wurde für diesen Kurs neu erzeugt.';
$string['task_warm_cache'] = 'Friction Radar: nächtliche Cachewarmer einplanen';
$string['task_warm_course'] = 'Friction Radar: Kurs-Cache erzeugen';

$string['friction_f01'] = 'Kognitive Überlastung';
$string['friction_f02'] = 'Didaktische Engstellen';
$string['friction_f03'] = 'Navigationschaos';
$string['friction_f04'] = 'Überladener Einstieg';
$string['friction_f05'] = 'Passive Anwesenheit';
$string['friction_f06'] = 'Zombie-Quizze';
$string['friction_f07'] = 'Unklare Erwartungen';
$string['friction_f08'] = 'Strukturelle Desorientierung';
$string['friction_f09'] = 'Ressourcenüberlastung';
$string['friction_f10'] = 'Versteckte Voraussetzungen';
$string['friction_f11'] = 'Frustriertes Scrollen';
$string['friction_f12'] = 'Deadline-Panik';

$string['explain_f01'] = 'Cognitive Overload beschreibt, wie viel gleichzeitige mentale Belastung ein Kurs von Lernenden verlangt. Der Wert wird anhand der Anzahl paralleler Aktivitäten, der Dichte verpflichtender Ressourcen innerhalb von Kursabschnitten sowie der durchschnittlichen textuellen Komplexität der Lernmaterialien im Analysezeitraum berechnet. Hohe Werte weisen auf eine gleichzeitige Überforderung durch zu viele Informationen hin.';
$string['explain_f02'] = 'Didaktische Stolpersteine kennzeichnen Schwächen im didaktischen Aufbau eines Kurses. Der Wert ergibt sich aus häufigen Wechseln zwischen Aktivitätstypen, fehlender didaktischer Progression zwischen Abschnitten sowie einer inkonsistenten Nutzung von Lernformaten. Hohe Werte deuten auf einen methodisch wenig stringenten Lernpfad hin.';
$string['explain_f03'] = 'Navigationschaos beschreibt, wie schwer es Lernenden fällt, sich innerhalb der Kursstruktur zu orientieren. Der Wert wird aus der Tiefe der Abschnittshierarchie, der Anzahl von Querverweisen zwischen entfernten Kursbereichen sowie häufigen Navigationssprüngen berechnet. Hohe Werte weisen auf Desorientierung und erhöhten kognitiven Navigationsaufwand hin.';
$string['explain_f04'] = 'Überambitionierter Einstieg misst, wie anspruchsvoll die Anfangsphase eines Kurses gestaltet ist. Der Wert basiert auf der Anzahl verpflichtender Aktivitäten und Ressourcen in den ersten Kursabschnitten sowie der erwarteten Arbeitsbelastung kurz nach Kursbeginn. Hohe Werte deuten darauf hin, dass Lernende frühzeitig stark gefordert werden.';
$string['explain_f05'] = 'Teilnahme-Theater identifiziert scheinbare Beteiligung ohne substanzielle Auseinandersetzung mit den Lerninhalten. Der Wert ergibt sich aus hoher Zugriffs- oder Login-Aktivität bei gleichzeitig geringer Interaktionstiefe, etwa durch minimale Forenbeiträge oder rein passives Aufrufen von Inhalten. Hohe Werte weisen auf oberflächliche Beteiligung hin.';
$string['explain_f06'] = 'Zombie-Quizzes beschreiben Prüfungsaktivitäten, die mechanisch und mit geringem Lerneffekt bearbeitet werden. Der Wert wird aus wiederholten Quizversuchen mit minimaler Leistungssteigerung, sehr kurzen Bearbeitungszeiten und geringer Antwortvariation berechnet. Hohe Werte deuten auf desengagiertes oder automatisiertes Bearbeitungsverhalten hin.';
$string['explain_f07'] = 'Unklare Erwartungen messen, wie transparent Anforderungen und Bewertungskriterien eines Kurses kommuniziert sind. Der Wert ergibt sich aus fehlenden oder verspäteten Aufgabenbeschreibungen, unklaren Bewertungshinweisen sowie häufigen Änderungen von Abgabeterminen. Hohe Werte weisen auf Unsicherheit bezüglich der erwarteten Leistungen hin.';
$string['explain_f08'] = 'Struktur-Paradox beschreibt Kurse, die formal gut strukturiert erscheinen, in der Praxis jedoch schwer verständlich sind. Der Wert wird aus einer tiefen Abschnittsverschachtelung in Kombination mit geringer Aktivitätsdichte sowie häufigem Zurücknavigieren berechnet. Hohe Werte deuten auf formale Struktur ohne funktionale Klarheit hin.';
$string['explain_f09'] = 'Ressourcen-Überversorgung misst das Ausmaß übermäßiger Bereitstellung von Lernmaterialien. Der Wert ergibt sich aus der Anzahl von Dateien, Seiten und externen Links pro Abschnitt, gewichtet gegen die tatsächliche Nutzung durch Lernende. Hohe Werte weisen auf ein Überangebot bei geringer effektiver Nutzung hin.';
$string['explain_f10'] = 'Unsichtbare Abhängigkeiten erfassen implizite Voraussetzungen, die für Lernende nicht explizit kommuniziert sind. Der Wert wird aus wiederholten Zugriffsversuchen auf gesperrte Aktivitäten, nicht erfüllten Zugriffsvoraussetzungen sowie Abhängigkeitsketten ohne klare Hinweise berechnet. Hohe Werte deuten auf unsichtbare Barrieren im Lernpfad hin.';
$string['explain_f11'] = 'Frust-Scroll misst ineffizientes Konsumverhalten von Inhalten. Der Wert wird aus langen Scroll-Sitzungen, wiederholten Seitenaufrufen ohne Interaktion sowie schnellem Durchscrollen umfangreicher Inhalte berechnet. Hohe Werte weisen auf Frustration durch schlecht strukturierte oder überlange Inhalte hin.';
$string['explain_f12'] = 'Deadline-Panik misst Zeitdruck, der durch gebündelte oder ungünstig verteilte Abgabetermine entsteht. Der Wert wird aus überlappenden Deadlines, kurzen Abgabeintervallen sowie erhöhter Last-Minute-Aktivität berechnet. Hohe Werte weisen auf stressfördernde Terminstrukturen hin.';

$string['what_to_do'] = 'Was tun bei hohem Wert?';

$string['action_f01'] = <<<TXT
Ein hoher Wert für Kognitive Überlastung bedeutet, dass Lernende gleichzeitig mit zu vielen verpflichtenden Aktivitäten, zu dichter Materialsammlung oder übermäßig komplexen Inhalten konfrontiert sind. Um diese Reibung zu senken, hilft vor allem konsequentes Reduzieren und kluges Sequenzieren.

Prüfe zunächst die ersten Kursabschnitte und entscheide, was wirklich zwingend notwendig ist. Wenn mehrere Aufgaben denselben Zweck erfüllen, fasse sie zusammen oder mache Teile davon optional. Lernende profitieren eher von wenigen, gut erklärten Schritten als von langen Pflichtlisten.

Verringere die Textkomplexität, wo immer es geht. Lange Fließtexte, verschachtelte Anweisungen und abstrakte Formulierungen erhöhen die kognitive Last. Teile Inhalte in kürzere Absätze, nutze Überschriften und formuliere Erwartungen einfach und eindeutig. Ergänze komplexe Passagen durch Beispiele, Grafiken oder kurze Videos.

Verteile Anforderungen über die Zeit. Vermeide es, viele Pflichtaufgaben in derselben Woche oder demselben Abschnitt zu bündeln. Eine klare Progression von leicht zu anspruchsvoll gibt Lernenden Orientierung und baut Selbstvertrauen auf.
TXT;
$string['action_f02'] = <<<TXT
Didaktische Engstellen entstehen, wenn Aktivitäten schlecht an Lernzielen ausgerichtet sind oder wenn Aufgabenbeschreibungen unklar, missverständlich oder widersprüchlich sind. Lernende erledigen dann Aufgaben, ohne den Sinn zu verstehen, was schnell zu Frust und Rückzug führt.

Überprüfe jede Aktivität darauf, ob sie ein konkretes Lernziel unterstützt. Formuliere explizit, warum es die Aktivität gibt und was Lernende daraus mitnehmen sollen. Wenn ein klarer Beitrag zum Lernziel fehlt, lohnt sich eine Überarbeitung oder das Entfernen.

Verbessere Aufgabenbeschreibungen, indem du Eingaben, erwartete Ergebnisse und Bewertungskriterien klar benennst. Vermeide implizite Annahmen über Vorwissen. Wo möglich, ergänze kurze Beispiele für eine gelungene Abgabe oder typische Fehler, die vermieden werden sollten.

Achte auf Konsistenz. Nutze ähnliche Formate, Begriffe und Strukturen über den Kurs hinweg. Wiedererkennbare Muster reduzieren „Entschlüsselungsarbeit“ und geben Lernenden Raum, sich auf Inhalte statt auf Interpretation zu konzentrieren.
TXT;
$string['action_f03'] = <<<TXT
Navigationschaos entsteht, wenn Lernende Materialien, Aktivitäten oder Orientierungspunkte im Kurs nur schwer finden. Häufige Ursachen sind uneinheitliche Benennungen, wechselnde Strukturen oder zu tiefe Verschachtelungen.

Schaffe eine klare, wiederholbare Kursstruktur. Verwende ein konsistentes Namensschema für Abschnitte und Aktivitäten. Bewährt ist zum Beispiel: Überblick, Materialien, Aktivitäten, ggf. Reflexion oder Abgabe.

Begrenze unnötige Verschachtelung. Tiefe Ebenen aus Ordnern, Seiten und Links erhöhen das Risiko, dass Lernende sich „verlaufen“. Wenn Gruppierung nötig ist, erkläre kurz, warum sie existiert und welcher nächste Schritt erwartet wird.

Nutze Labels und Abschnittszusammenfassungen als Orientierung. Ein kurzer Satz, was in einem Abschnitt passiert und wie er in den Kursablauf passt, reduziert Suchaufwand spürbar.
TXT;
$string['action_f04'] = <<<TXT
Ein überambitionierter Einstieg bedeutet, dass Lernende direkt zu Kursbeginn stark gefordert werden. Das kann überfordern, bevor Routinen entstehen und bevor Lernende Sicherheit im Kursaufbau gewinnen.

Sieh dir die ersten ein bis zwei Abschnitte an und zähle, wie viele verpflichtende Aktivitäten und Ressourcen dort sofort anstehen. Verschiebe anspruchsvolle Aufgaben, umfangreiche Lektüre oder bewertete Abgaben nach Möglichkeit in spätere Phasen.

Nutze die Startphase zur Orientierung. Führe Ziele, Struktur und Erwartungen schrittweise ein. Niedrigschwellige Einstiegsaufgaben wie kurze Vorstellungsrunden, einfache Selbsttests oder geführte Rundgänge helfen, sich zu akklimatisieren.

Frühe Erfolgserlebnisse sind wichtig. Gestalte die ersten Aufgaben so, dass die Mehrheit sie gut bewältigen kann. Das reduziert Abbruchrisiken und stärkt die Motivation.
TXT;
$string['action_f05'] = <<<TXT
„Passive Anwesenheit“ bzw. Participation Theatre beschreibt Situationen, in denen Lernende zu Beteiligung verpflichtet werden, ohne dass diese Beteiligung spürbare Wirkung oder sinnvolles Feedback hat. Aktivität wird dann zur Pflichtübung statt zum Lernprozess.

Prüfe, ob Beteiligungsaufgaben tatsächlich zu Reflexion, Austausch oder Erkenntnis führen. Wenn Forenbeiträge oder Abgaben verpflichtend sind, sorge dafür, dass sie beantwortet, kommentiert oder später weiterverwendet werden.

Reduziere künstliche Beteiligungsanforderungen. Pflichtposts ohne Interaktion fördern oberflächliche Beiträge. Besser sind wenige, gut fokussierte Impulse mit klaren Leitfragen, die echte Diskussion auslösen können.

Setze stärker auf authentische Aufgabenformate. Kollaborative Dokumente, Peer-Feedback oder optionale Diskussionsanlässe erzeugen oft mehr echte Beteiligung als formale Nachweispflichten.
TXT;
$string['action_f06'] = <<<TXT
Zombie-Quizze sind Tests, die über lange Zeit unverändert wiederverwendet werden und kaum diagnostischen oder lernförderlichen Nutzen bieten. Lernende klicken sich dann mechanisch durch, ohne wirklich etwas mitzunehmen.

Überarbeite regelmäßig die Fragen. Entferne veraltete, unklare oder missverständliche Items und stelle sicher, dass alle Fragen zum aktuellen Kursinhalt passen.

Nutze Feedback gezielt. Sofortiges, erklärendes Feedback macht ein Quiz zu einem Lernwerkzeug. Schon kurze Begründungen, warum eine Antwort richtig oder falsch ist, steigern den Lerneffekt deutlich.

Variiere Quizformate. Eine Mischung aus formativen Übungsquizzen, mehreren Versuchen und Selbsttests reduziert Monotonie und erhöht die Motivation.
TXT;
$string['action_f07'] = <<<TXT
Unklare Erwartungen entstehen, wenn Lernende nicht sicher wissen, was genau verlangt wird, um erfolgreich zu sein. Das betrifft unpräzise Bewertungsmaßstäbe, fehlende Beschreibungen oder implizite Annahmen.

Stelle sicher, dass alle bewerteten Aktivitäten eine klare Beschreibung haben und, wo sinnvoll, Kriterien oder Rubrics enthalten. Lernende sollten vor der Abgabe verstehen, wie bewertet wird.

Mache Arbeitsaufwand transparent. Gib grobe Zeitangaben und nenne Formatvorgaben (z.B. Umfang, Dateityp, Abgabemodus). Das erleichtert Planung und reduziert Stress.

Prüfe Aufgaben aus Lernendenperspektive. Was Lehrenden offensichtlich erscheint, ist für Studierende oft nicht explizit genug. Formuliere daher lieber einmal zu klar als zu knapp.
TXT;
$string['action_f08'] = <<<TXT
Strukturelle Desorientierung entsteht, wenn Lernende kein stabiles mentales Modell der Kursstruktur aufbauen können. Ursachen sind häufig inkonsistente Organisation oder nachträgliche Strukturänderungen.

Halte die Kursstruktur über das Semester möglichst stabil. Vermeide es, Aktivitäten zu verschieben oder Abschnitte umzubenennen, sobald Lernende begonnen haben, damit zu arbeiten.

Arbeite mit wiederkehrenden Mustern. Beispiel: Jeder Abschnitt folgt derselben Reihenfolge: Überblick, Materialien, Aktivitäten, ggf. Abgabe. Wiederholung schafft Orientierung.

Setze strukturelle Signale. Abschnittszusammenfassungen, visuelle Trennelemente und konsistente Icons helfen, die Struktur schnell zu erfassen und reduzieren Verwirrung.
TXT;
$string['action_f09'] = <<<TXT
Ressourcenüberlastung entsteht durch eine zu große Menge an Dateien, Links und externen Materialien. Lernende verlieren den Überblick und können schwer entscheiden, was wirklich wichtig ist.

Führe regelmäßig einen Ressourcen-Check durch. Entferne veraltete oder doppelte Materialien und markiere optionale Ressourcen klar als „optional“.

Setze auf Qualität statt Masse. Wenige, gut ausgewählte Ressourcen sind oft wirksamer als eine umfassende Materialsammlung.

Gib Orientierung durch kurze Hinweise. Ein Satz, warum eine Ressource relevant ist und wann sie genutzt werden soll, hilft Lernenden, Prioritäten zu setzen.
TXT;
$string['action_f10'] = <<<TXT
Versteckte Voraussetzungen liegen vor, wenn Aktivitäten auf Vorwissen, Tools oder vorherigen Inhalten basieren, ohne dass dies ausdrücklich genannt wird. Lernende scheitern dann, ohne zu verstehen, warum.

Identifiziere Voraussetzungen je Aktivität. Wenn bestimmte Inhalte, Fähigkeiten oder das Abschließen früherer Schritte nötig sind, benenne das sichtbar.

Nutze Voraussetzungen und Verfügbarkeitsbedingungen transparent. Abhängigkeiten sollten nicht als Überraschung auftreten, sondern begründet und nachvollziehbar sein.

Biete bei Bedarf kurze Auffrischungen oder Verweise auf Grundlagenmaterial an. Das unterstützt heterogene Lerngruppen und reduziert unnötige Frustration.
TXT;
$string['action_f11'] = <<<TXT
Frustriertes Scrollen entsteht, wenn Seiten sehr lang sind und kaum Struktur bieten. Lernende müssen viel scrollen, um Anweisungen, Materialien oder den nächsten Schritt zu finden.

Teile lange Seiten in kleinere Einheiten. Nutze Überschriften, Akkordeons oder mehrere Seiten, um die Lesbarkeit zu erhöhen.

Platziere die wichtigste Information nach oben. Lernende sollten schnell erkennen können, was als Nächstes zu tun ist, ohne erst weit nach unten zu scrollen.

Nutze visuelle Struktur bewusst. Weißraum, Zwischenüberschriften und kurze Absätze verbessern Orientierung deutlich und reduzieren Suchaufwand.
TXT;
$string['action_f12'] = <<<TXT
Deadline-Panik entsteht, wenn viele Abgabetermine eng beieinanderliegen oder wenn Deadlines schlecht kommuniziert werden. Das erhöht Stress und führt häufiger zu verpassten Abgaben.

Prüfe den Kurskalender auf Terminballungen. Verteile Deadlines möglichst gleichmäßig über die Wochen.

Kommuniziere Termine klar und frühzeitig. Verwende konsistente Benennungen und stelle sicher, dass Deadlines sowohl in der Aktivität als auch im Kalender sichtbar sind.

Denke über sinnvolle Flexibilität nach. Wo es fachlich vertretbar ist, helfen Kulanzzeiten, mehrere Versuche oder gestaffelte Abgaben, unnötigen Druck zu reduzieren, ohne akademische Standards zu senken.
TXT;

$string['notes_f01'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn viele verpflichtende Aktivitäten parallel auftreten. B steigt, wenn verpflichtende Aktivitäten von vielen verpflichtenden Ressourcen begleitet werden. C steigt bei hoher durchschnittlicher textueller Komplexität von Beschreibungen und Inhalten.';
$string['notes_f02'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn Aktivitätstypen häufig wechseln. B steigt, wenn anspruchsvolle Aktivitäten ohne unterstützende Materialien in der Nähe stehen. C ist aktuell ein Platzhalter (0,0), bis Versuchs-/Verzögerungsmetriken implementiert sind.';
$string['notes_f03'] = 'Berechnet über die letzten {$a} Tage. A steigt bei vielen nicht-leeren Abschnitten (Fragmentierung). B steigt, wenn Aktivitäten ungleich auf Abschnitte verteilt sind. C steigt, wenn viele unterschiedliche Aktivitätstypen gemischt werden (Typ-Entropie).';
$string['notes_f04'] = 'Berechnet über die letzten {$a} Tage. A steigt bei vielen verpflichtenden Aktivitäten zu Kursbeginn. B spiegelt eine hohe frühe Arbeitsbelastung wider. C steigt bei komplexen einführenden Inhalten.';
$string['notes_f05'] = 'Berechnet über die letzten {$a} Tage. A zählt Lernende, die den Kurs wiederholt aufrufen, aber keine substanziellen Aktionen ausführen. B spiegelt geringe Interaktionstiefe wider (wenige sinnvolle Aktionen pro Betrachter). C erfasst die Lücke zwischen Betrachtenden und tatsächlich Engagierten. Substantielle Aktionen werden über logstore_standard_log angenähert (z. B. erstellt/eingereicht/gepostet).';
$string['notes_f06'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn Quizze keinerlei Versuche erhalten (Zombie-Quizze). B steigt, wenn viele Versuche nicht abgeschlossen werden (Abbruch). C steigt, wenn Quizze nur von sehr wenigen Lernenden bearbeitet werden (geringe Teilnahme). Versuche werden aus quiz_attempts abgeleitet und auf studierendenähnliche Rollen gefiltert.';
$string['notes_f07'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn verpflichtende Aktivitäten kein klares Fälligkeitsdatum haben. B steigt, wenn bewertete Aktivitäten keine aussagekräftige Beschreibung enthalten. C steigt, wenn zu Kursbeginn eine zentrale Übersicht oder Erwartungsklärung fehlt.';
$string['notes_f08'] = 'Berechnet über die letzten {$a} Tage. A steigt bei hoher formaler Strukturdichte (viele Module pro Abschnitt). B steigt, wenn viele redundante Strukturelemente (Labels, Bücher, Ordner) konkurrieren. C steigt bei stark variierenden Abschnittsgrößen, was die Vorhersagbarkeit der Struktur verringert.';
$string['notes_f09'] = 'Berechnet über die letzten {$a} Tage. A steigt bei vielen Ressourcen pro Abschnitt (Ressourcendichte). B steigt, wenn Ressourcen gegenüber Aktivitäten dominieren (Ressourcenanteil). C steigt, wenn viele Ressourcen reine Datei-Ressourcen sind (Redundanz-Proxy).';
$string['notes_f10'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn viele Aktivitäten Zugriffsbeschränkungen haben. B steigt, wenn diese Beschränkungen ohne sichtbare Erklärung dargestellt werden. C steigt bei Abhängigkeiten von mehreren Bedingungen.';
$string['notes_f11'] = 'Berechnet über die letzten {$a} Tage. A steigt mit zunehmender Kurslänge und -dichte. B steigt bei sehr großen Abschnitten, die intensives Scrollen erzwingen. C steigt, wenn wenige Orientierungselemente (Labels) zur Strukturierung genutzt werden.';
$string['notes_f12'] = 'Berechnet über die letzten {$a} Tage. A steigt bei hoher Dichte von Abgabeterminen. B steigt, wenn mehrere Deadlines auf denselben Tag fallen. C steigt, wenn Abgaben sehr kurzfristig gesetzt werden und wenig Vorbereitungszeit bleibt.';

$string['privacy:metadata'] = 'Friction Radar speichert nur aggregierte kursbezogene Scores im Cache und keine personenbezogenen Daten.';

$string['ui_score'] = 'Score';
$string['ui_formula'] = 'Formel';
$string['ui_inputs'] = 'Berechnungswerte';
$string['ui_param'] = 'Parameter';
$string['ui_value'] = 'Wert';
$string['ui_notes'] = 'Hinweise';
