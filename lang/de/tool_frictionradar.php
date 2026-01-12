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
$string['action_f01'] = 'Gleichzeitige Anforderungen reduzieren. Pflichtmaterial pro Abschnitt begrenzen, lange Seiten in kleinere Einheiten teilen und am Abschnittsende kurze Zusammenfassungen ergänzen. Optionale Ressourcen klar als „Vertiefung“ kennzeichnen statt als Pflichtschritt.';
$string['action_f02'] = 'Didaktische Progression sichtbar machen. Pro Abschnitt kurze Lernziele ergänzen, Aktivitätstypen konsistent einsetzen und neue Formate kurz einführen. Redundante Schritte entfernen und für jede Aktivität den Zweck im Lernpfad klar machen.';
$string['action_f03'] = 'Orientierung verbessern. Zu tiefe Strukturen abflachen, Querverweise zwischen weit entfernten Bereichen reduzieren und pro Abschnitt einen kurzen „Wo bin ich?“-Einstieg ergänzen. Einheitliche Benennung nutzen und einen stabilen Navigationspfad (z. B. Wochenstruktur) bevorzugen.';
$string['action_f04'] = 'Einstiegshürde senken. Komplexe Aufgaben aus den ersten Abschnitten herausnehmen, ein kurzes Onboarding bereitstellen und eine niedrigschwellige erste Aktivität anbieten. Erwartungen früh klären, aber Arbeitslast in den ersten Wochen gleichmäßiger verteilen.';
$string['action_f05'] = 'Substanzielle Interaktion erhöhen. Passive Aufrufe durch konkrete Micro-Prompts ersetzen (z. B. ein Reflexionspost, eine Peer-Antwort). Abschlusskriterien so setzen, dass echte Beteiligung zählt, nicht nur Zugriff. Beispiele für „gute Beteiligung“ geben und leere Checklisten vermeiden.';
$string['action_f06'] = 'Quiz-Lerneffekt erhöhen. Wiederholte Versuche ohne Feedback reduzieren, aussagekräftiges Feedback pro Frage ergänzen und Frageformate variieren. Lieber weniger, dafür hochwertige Quizzes einsetzen und Reflexion fördern (z. B. kurze Begründung bei falschen Antworten).';
$string['action_f07'] = 'Anforderungen klären. Aufgabenbeschreibungen, Bewertungskriterien und Beispiele bereitstellen. Deadlines möglichst stabil halten und Änderungen früh kommunizieren. Einen eigenen Abschnitt „Erwartungen & Bewertung“ als zentrale Quelle für Regeln und Termine anlegen.';
$string['action_f08'] = 'Struktur funktional machen, nicht nur formal. Verschachtelung reduzieren, pro Abschnitt eine inhaltlich geschlossene Lerneinheit sicherstellen und leere Container-Abschnitte vermeiden. Prüfen, ob die Struktur dem realen Navigationsverhalten entspricht, und entsprechend anpassen.';
$string['action_f09'] = 'Ressourcen kuratieren. Duplikate entfernen, Materialien nach Zweck bündeln (Kern vs. optional) und die Anzahl der Elemente pro Abschnitt begrenzen. Kurze Annotationen („Warum ist das relevant?“) ergänzen und Links bei Bedarf in einer gut strukturierten Übersichtsseite zusammenführen.';
$string['action_f10'] = 'Voraussetzungen sichtbar machen. Abhängigkeiten und Zugriffsvoraussetzungen früh kommunizieren, bevor Lernende an Sperren stoßen. Hinweise „Benötigt vorher“ ergänzen, auf Vorleistungen verlinken und Abhängigkeitsketten kurz halten. Bedingungen, wo möglich, vereinfachen.';
$string['action_f11'] = 'Scroll-Reibung reduzieren. Lange Seiten in Abschnitte mit Überschriften gliedern, Inhaltsverzeichnis ergänzen und wichtige Aktionen/Links nach oben ziehen. Lieber mehrere kurze Seiten statt einer endlosen Seite, und zentrale Inhalte nicht „vergraben“.';
$string['action_f12'] = 'Deadlines entzerren. Abgabetermine verteilen, mehrere Abgaben im selben Zeitraum vermeiden und frühere Zwischenmeilensteine anbieten. Einen konsistenten Wochenrhythmus nutzen, Kalender sichtbar machen und Abgabefenster klar kommunizieren, um Last-Minute-Druck zu senken.';

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
