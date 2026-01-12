<?php
/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

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

$string['action_f01']  = 'Ein hoher Wert für Kognitive Überlastung zeigt an, dass Lernende gleichzeitig mit zu vielen verpflichtenden Aktivitäten, dichten Ressourcen oder komplexen Materialien konfrontiert werden. ';
$string['action_f01'] .= 'Um diese Reibung zu reduzieren, sollten Inhalte bewusst reduziert und Anforderungen sinnvoll sequenziert werden. ';
$string['action_f01'] .= 'Überprüfen Sie zunächst die ersten Kursabschnitte und identifizieren Sie, welche Aktivitäten wirklich essenziell sind. ';
$string['action_f01'] .= 'Wenn mehrere Aufgaben denselben Zweck erfüllen, können sie zusammengeführt oder teilweise optional gestaltet werden. ';
$string['action_f01'] .= 'Lernende profitieren deutlich mehr von wenigen klar erklärten Aktivitäten als von langen Pflichtlisten. ';
$string['action_f01'] .= 'Reduzieren Sie, wo möglich, die textliche Komplexität. Lange Beschreibungen, verschachtelte Anweisungen und abstrakte Formulierungen erhöhen die kognitive Belastung. ';
$string['action_f01'] .= 'Gliedern Sie Texte in kurze Absätze, verwenden Sie Überschriften und formulieren Sie Erwartungen klar und einfach. ';
$string['action_f01'] .= 'Ergänzen Sie komplexe Inhalte durch Visualisierungen, Beispiele oder kurze Videos. ';
$string['action_f01'] .= 'Verteilen Sie Anforderungen zeitlich. Vermeiden Sie es, viele verpflichtende Aufgaben in derselben Woche oder im selben Abschnitt zu platzieren. ';
$string['action_f01'] .= 'Ein klarer Aufbau von einfachen zu komplexen Anforderungen hilft Lernenden, Sicherheit aufzubauen, bevor höhere Anforderungen gestellt werden.';

$string['action_f02']  = 'Didaktische Engstellen entstehen, wenn Lernaktivitäten schlecht auf Lernziele abgestimmt sind oder Anweisungen unklar bzw. missverständlich formuliert sind. ';
$string['action_f02'] .= 'Lernende bearbeiten Aufgaben dann häufig, ohne deren Sinn zu verstehen, was Frustration und Demotivation begünstigt. ';
$string['action_f02'] .= 'Prüfen Sie, ob jede Aktivität klar einem Lernziel zugeordnet ist. ';
$string['action_f02'] .= 'Formulieren Sie explizit, warum eine Aufgabe existiert und welchen Lernertrag sie haben soll. ';
$string['action_f02'] .= 'Trägt eine Aktivität nicht eindeutig zu einem Lernziel bei, sollte sie überarbeitet oder entfernt werden. ';
$string['action_f02'] .= 'Verbessern Sie Aufgabenbeschreibungen, indem Sie Eingaben, erwartete Ergebnisse und Bewertungskriterien klar benennen. ';
$string['action_f02'] .= 'Vermeiden Sie implizite Annahmen über Vorwissen. ';
$string['action_f02'] .= 'Wo sinnvoll, helfen kurze Beispiele für gelungene Abgaben oder typische Fehler. ';
$string['action_f02'] .= 'Achten Sie auf Konsistenz. Einheitliche Formate, Begriffe und Strukturen erleichtern die Orientierung. ';
$string['action_f02'] .= 'Wenn Lernende Muster erkennen, können sie sich auf das Lernen konzentrieren statt auf das Entschlüsseln von Aufgaben.';

$string['action_f03']  = 'Navigationschaos entsteht, wenn Lernende Materialien, Aktivitäten oder Orientierungspunkte nur schwer finden. ';
$string['action_f03'] .= 'Ursache sind häufig inkonsistente Strukturen oder eine zu tiefe Verschachtelung des Kursaufbaus. ';
$string['action_f03'] .= 'Schaffen Sie eine klare und wiederkehrende Struktur. ';
$string['action_f03'] .= 'Verwenden Sie ein konsistentes Benennungsschema für Abschnitte und Aktivitäten. ';
$string['action_f03'] .= 'Beispielsweise kann jeder Themenblock mit einer Übersicht beginnen, gefolgt von Materialien und anschließend Aktivitäten. ';
$string['action_f03'] .= 'Vermeiden Sie unnötige Verschachtelungen. Tiefe Ordnerstrukturen und viele Verlinkungen erhöhen das Risiko, dass Lernende die Orientierung verlieren. ';
$string['action_f03'] .= 'Wenn Inhalte gruppiert werden müssen, erklären Sie den Zweck und den nächsten Schritt. ';
$string['action_f03'] .= 'Nutzen Sie Beschriftungen und Abschnittsbeschreibungen zur Orientierung. ';
$string['action_f03'] .= 'Schon ein kurzer erklärender Satz kann den Navigationsaufwand erheblich reduzieren.';

$string['action_f04']  = 'Ein überambitionierter Einstieg liegt vor, wenn Lernende unmittelbar nach Kursbeginn mit hohen Anforderungen konfrontiert werden. ';
$string['action_f04'] .= 'Dies kann überfordern, bevor Routinen oder Sicherheit aufgebaut wurden. ';
$string['action_f04'] .= 'Überprüfen Sie die ersten ein bis zwei Kursabschnitte. ';
$string['action_f04'] .= 'Zählen Sie, wie viele verpflichtende Aktivitäten und Ressourcen dort verlangt werden. ';
$string['action_f04'] .= 'Komplexe Aufgaben, Prüfungen oder umfangreiche Lektüren lassen sich oft sinnvoll in spätere Abschnitte verschieben. ';
$string['action_f04'] .= 'Nutzen Sie die Anfangsphase zur Orientierung. ';
$string['action_f04'] .= 'Führen Sie Kursziele, Struktur und Erwartungen schrittweise ein. ';
$string['action_f04'] .= 'Niedrigschwellige Aktivitäten wie kurze Vorstellungsaufgaben, einfache Quizze oder geführte Rundgänge erleichtern den Einstieg. ';
$string['action_f04'] .= 'Frühe Erfolgserlebnisse sind entscheidend. ';
$string['action_f04'] .= 'Gestalten Sie erste Aufgaben so, dass sie von den meisten Lernenden erfolgreich bewältigt werden können.';

$string['action_f05']  = 'Passive Anwesenheit beschreibt Situationen, in denen Lernende zur Teilnahme verpflichtet sind, ohne dass diese Teilnahme eine inhaltliche Wirkung entfaltet. ';
$string['action_f05'] .= 'Aktivitäten dienen dann eher dem Nachweis von Aktivität als dem Lernen. ';
$string['action_f05'] .= 'Prüfen Sie, ob Teilnahmeaktivitäten Reflexion, Austausch oder Wissenskonstruktion fördern. ';
$string['action_f05'] .= 'Wenn Forenbeiträge oder Abgaben verpflichtend sind, sollten sie Rückmeldung erhalten oder sichtbar in den Kursverlauf eingebunden sein. ';
$string['action_f05'] .= 'Reduzieren Sie künstliche Teilnahmepflichten. ';
$string['action_f05'] .= 'Verpflichtende Beiträge ohne Interaktion führen häufig zu oberflächlichen Antworten. ';
$string['action_f05'] .= 'Besser sind wenige, klar fokussierte Beiträge mit präzisen Fragestellungen. ';
$string['action_f05'] .= 'Ersetzen Sie formale Teilnahme nach Möglichkeit durch authentische Aufgaben. ';
$string['action_f05'] .= 'Gemeinsame Dokumente, Peer-Feedback oder optionale Diskussionsimpulse fördern oft echtes Engagement.';

$string['action_f06']  = 'Zombie-Quizze sind Tests, die über lange Zeit unverändert wiederverwendet werden und kaum diagnostischen oder lernförderlichen Wert haben. ';
$string['action_f06'] .= 'Lernende bearbeiten sie häufig mechanisch und ohne Reflexion. ';
$string['action_f06'] .= 'Überprüfen Sie Quizfragen regelmäßig auf Aktualität und Verständlichkeit. ';
$string['action_f06'] .= 'Entfernen Sie veraltete oder missverständliche Fragen und stellen Sie sicher, dass sie zum aktuellen Kursinhalt passen. ';
$string['action_f06'] .= 'Nutzen Sie Feedback gezielt. ';
$string['action_f06'] .= 'Unmittelbares, erklärendes Feedback macht Quizze zu Lerninstrumenten. ';
$string['action_f06'] .= 'Schon kurze Erläuterungen zu richtigen und falschen Antworten können den Lernerfolg deutlich steigern. ';
$string['action_f06'] .= 'Variieren Sie die Quizformate. ';
$string['action_f06'] .= 'Eine Mischung aus Übungsquizzen, formativen Tests und Selbstüberprüfungen erhöht die Motivation.';

$string['action_f07']  = 'Unklare Erwartungen entstehen, wenn Lernende nicht wissen, was für einen erfolgreichen Abschluss erforderlich ist. ';
$string['action_f07'] .= 'Dies betrifft unklare Bewertungskriterien, fehlende Beschreibungen oder implizite Annahmen. ';
$string['action_f07'] .= 'Stellen Sie sicher, dass alle bewerteten Aktivitäten klar beschrieben sind. ';
$string['action_f07'] .= 'Bewertungskriterien oder Rubrics sollten, wenn möglich, vor der Abgabe sichtbar sein. ';
$string['action_f07'] .= 'Machen Sie den Arbeitsaufwand transparent. ';
$string['action_f07'] .= 'Geben Sie ungefähre Zeitbedarfe und Abgabeformate an, damit Lernende besser planen können. ';
$string['action_f07'] .= 'Betrachten Sie Aufgaben aus der Perspektive der Lernenden. ';
$string['action_f07'] .= 'Was für erfahrene Lehrende selbstverständlich ist, ist für Studierende oft nicht klar.';

$string['action_f08']  = 'Strukturelle Desorientierung tritt auf, wenn Lernende kein klares mentales Modell des Kursaufbaus entwickeln können. ';
$string['action_f08'] .= 'Häufige Ursachen sind inkonsistente Organisation oder häufige strukturelle Änderungen. ';
$string['action_f08'] .= 'Halten Sie die Kursstruktur während des Semesters stabil. ';
$string['action_f08'] .= 'Vermeiden Sie es, Aktivitäten zu verschieben oder Abschnitte umzubenennen, nachdem der Kurs begonnen hat. ';
$string['action_f08'] .= 'Nutzen Sie wiederkehrende Muster. ';
$string['action_f08'] .= 'Beispielsweise kann jeder Abschnitt gleich aufgebaut sein: Übersicht, Materialien, Aktivitäten, Bewertung. ';
$string['action_f08'] .= 'Setzen Sie strukturierende Signale ein. ';
$string['action_f08'] .= 'Abschnittsbeschreibungen, visuelle Trennungen und konsistente Icons erleichtern die Orientierung.';

$string['action_f09']  = 'Ressourcenüberlastung entsteht durch eine große Menge an Dateien, Links und externen Materialien. ';
$string['action_f09'] .= 'Lernende haben dann Schwierigkeiten zu erkennen, was wirklich relevant ist. ';
$string['action_f09'] .= 'Überprüfen Sie Ihre Materialien regelmäßig. ';
$string['action_f09'] .= 'Entfernen Sie veraltete oder redundante Ressourcen und kennzeichnen Sie optionale Inhalte eindeutig. ';
$string['action_f09'] .= 'Setzen Sie auf Qualität statt Quantität. ';
$string['action_f09'] .= 'Wenige, gut ausgewählte Materialien sind oft wirksamer als eine vollständige Sammlung. ';
$string['action_f09'] .= 'Geben Sie Orientierung. ';
$string['action_f09'] .= 'Kurze Hinweise, warum eine Ressource wichtig ist und wann sie genutzt werden soll, helfen bei der Auswahl.';

$string['action_f10']  = 'Versteckte Voraussetzungen liegen vor, wenn Aktivitäten auf Vorwissen, Werkzeuge oder Inhalte aufbauen, die nicht explizit genannt werden. ';
$string['action_f10'] .= 'Lernende scheitern dann, ohne die Ursache zu verstehen. ';
$string['action_f10'] .= 'Identifizieren Sie die Voraussetzungen jeder Aktivität. ';
$string['action_f10'] .= 'Wenn Vorwissen oder der Abschluss vorheriger Aufgaben notwendig ist, sollte dies klar kommuniziert werden. ';
$string['action_f10'] .= 'Nutzen Sie bedingte Verfügbarkeit bewusst. ';
$string['action_f10'] .= 'Machen Sie Abhängigkeiten sichtbar und erklären Sie ihren Zweck, statt Lernende durch Ausprobieren scheitern zu lassen. ';
$string['action_f10'] .= 'Stellen Sie bei Bedarf Auffrischungsmaterial oder Verweise bereit. ';
$string['action_f10'] .= 'So unterstützen Sie Lernende mit unterschiedlichen Vorkenntnissen.';

$string['action_f11']  = 'Frustriertes Scrollen entsteht durch lange, unstrukturierte Seiten, auf denen relevante Informationen nur durch intensives Scrollen gefunden werden. ';
$string['action_f11'] .= 'Zerteilen Sie lange Inhalte in kleinere Einheiten. ';
$string['action_f11'] .= 'Überschriften, Akkordeons oder separate Seiten verbessern die Lesbarkeit deutlich. ';
$string['action_f11'] .= 'Platzieren Sie zentrale Informationen am Seitenanfang. ';
$string['action_f11'] .= 'Lernende sollten ohne langes Scrollen erkennen können, was als Nächstes zu tun ist. ';
$string['action_f11'] .= 'Nutzen Sie visuelle Struktur gezielt. ';
$string['action_f11'] .= 'Weißraum, Absätze und klare Gliederung erleichtern die Orientierung.';

$string['action_f12']  = 'Deadline-Panik entsteht, wenn viele Abgabefristen zeitlich dicht beieinanderliegen oder unklar kommuniziert werden. ';
$string['action_f12'] .= 'Dies führt zu Stress und erhöht das Risiko verpasster Abgaben. ';
$string['action_f12'] .= 'Überprüfen Sie den Kurskalender auf Häufungen von Deadlines. ';
$string['action_f12'] .= 'Verteilen Sie Abgaben möglichst gleichmäßig über das Semester. ';
$string['action_f12'] .= 'Kommunizieren Sie Fristen klar und frühzeitig. ';
$string['action_f12'] .= 'Achten Sie auf konsistente Bezeichnungen und darauf, dass Deadlines sowohl in der Aktivität als auch im Kalender sichtbar sind. ';
$string['action_f12'] .= 'Erwägen Sie flexible Regelungen. ';
$string['action_f12'] .= 'Kulanzfristen oder mehrere Versuche können unnötigen Stress reduzieren, ohne akademische Standards zu gefährden.';
