<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Friction Radar';
$string['plugindescription'] = 'Frühe Hinweise auf Lernreibung auf Basis aggregierter Kursaktivitäten.';
$string['navitem'] = 'Friction Radar';
$string['page_title'] = 'Lernreibung im Kurs';
$string['page_subtitle'] = 'Aggregierte kursbezogene Signale (rollierendes 6-Wochen-Fenster).';
$string['overall_score'] = 'Gesamtwert der Lernreibung';
$string['friction_clock_aria'] = 'Friction-Uhr';
$string['generated_at'] = 'Erzeugt';
$string['window'] = 'Fenster';
$string['days'] = 'Tage';
$string['no_data'] = 'Noch nicht genug Daten. Werte erscheinen nach der nächtlichen Cache-Erzeugung.';
$string['warmcache_now'] = 'Jetzt Werte erzeugen';
$string['warmcache_done'] = 'Cache wurde für diesen Kurs neu erzeugt.';
$string['task_warm_cache'] = 'Friction Radar: nächtliche Cache-Warmer einplanen';
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
$string['explain_f06'] = 'Zombie-Quizze beschreiben Prüfungsaktivitäten, die mechanisch und mit geringem Lerneffekt bearbeitet werden. Der Wert wird aus wiederholten Quizversuchen mit minimaler Leistungssteigerung, sehr kurzen Bearbeitungszeiten und geringer Antwortvariation berechnet. Hohe Werte deuten auf desengagiertes oder automatisiertes Bearbeitungsverhalten hin.';
$string['explain_f07'] = 'Unklare Erwartungen messen, wie transparent Anforderungen und Bewertungskriterien eines Kurses kommuniziert sind. Der Wert ergibt sich aus fehlenden oder verspäteten Aufgabenbeschreibungen, unklaren Bewertungshinweisen sowie häufigen Änderungen von Abgabeterminen. Hohe Werte weisen auf Unsicherheit bezüglich der erwarteten Leistungen hin.';
$string['explain_f08'] = 'Struktur-Paradox beschreibt Kurse, die formal gut strukturiert erscheinen, in der Praxis jedoch schwer verständlich sind. Der Wert wird aus einer tiefen Abschnittsverschachtelung in Kombination mit geringer Aktivitätsdichte sowie häufigem Zurücknavigieren berechnet. Hohe Werte deuten auf formale Struktur ohne funktionale Klarheit hin.';
$string['explain_f09'] = 'Ressourcen-Überversorgung misst das Ausmaß übermäßiger Bereitstellung von Lernmaterialien. Der Wert ergibt sich aus der Anzahl von Dateien, Seiten und externen Links pro Abschnitt, gewichtet gegen die tatsächliche Nutzung durch Lernende. Hohe Werte weisen auf ein Überangebot bei geringer effektiver Nutzung hin.';
$string['explain_f10'] = 'Unsichtbare Abhängigkeiten erfassen implizite Voraussetzungen, die für Lernende nicht explizit kommuniziert sind. Der Wert wird aus wiederholten Zugriffsversuchen auf gesperrte Aktivitäten, nicht erfüllten Zugriffsvoraussetzungen sowie Abhängigkeitsketten ohne klare Hinweise berechnet. Hohe Werte deuten auf unsichtbare Barrieren im Lernpfad hin.';
$string['explain_f11'] = 'Frust-Scrollen misst ineffizientes Konsumverhalten von Inhalten. Der Wert wird aus langen Scroll-Sitzungen, wiederholten Seitenaufrufen ohne Interaktion sowie schnellem Durchscrollen umfangreicher Inhalte berechnet. Hohe Werte weisen auf Frustration durch schlecht strukturierte oder überlange Inhalte hin.';
$string['explain_f12'] = 'Deadline-Panik misst Zeitdruck, der durch gebündelte oder ungünstig verteilte Abgabetermine entsteht. Der Wert wird aus überlappenden Deadlines, kurzen Abgabeintervallen sowie erhöhter Last-Minute-Aktivität berechnet. Hohe Werte weisen auf stressfördernde Terminstrukturen hin.';

$string['what_to_do'] = 'Was tun bei hohem Wert?';

$string['notes_f01'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn viele verpflichtende Aktivitäten parallel auftreten. B steigt, wenn verpflichtende Aktivitäten von vielen verpflichtenden Ressourcen begleitet werden. C steigt bei hoher durchschnittlicher textueller Komplexität von Beschreibungen und Inhalten.';
$string['notes_f02'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn Aktivitätstypen häufig wechseln. B steigt, wenn anspruchsvolle Aktivitäten ohne unterstützende Materialien in der Nähe stehen. C ist aktuell ein Platzhalter (0,0), bis Versuchs-/Verzögerungsmetriken implementiert sind.';
$string['notes_f03'] = 'Berechnet über die letzten {$a} Tage. A steigt bei vielen nicht leeren Abschnitten (Fragmentierung). B steigt, wenn Aktivitäten ungleich auf Abschnitte verteilt sind. C steigt, wenn viele unterschiedliche Aktivitätstypen gemischt werden (Typ-Entropie).';
$string['notes_f04'] = 'Berechnet über die letzten {$a} Tage. A steigt bei vielen verpflichtenden Aktivitäten zu Kursbeginn. B spiegelt eine hohe frühe Arbeitsbelastung wieder. C steigt bei komplexen einführenden Inhalten.';
$string['notes_f05'] = 'Berechnet über die letzten {$a} Tage. A zählt Lernende, die den Kurs wiederholt aufrufen, aber keine substanziellen Aktionen ausführen. B spiegelt geringe Interaktionstiefe wieder (wenige sinnvolle Aktionen pro Betrachter). C erfasst die Lücke zwischen Betrachtenden und tatsächlich Engagierten. Substantielle Aktionen werden über logstore_standard_log angenähert (z. B. erstellt/eingereicht/gepostet).';
$string['notes_f06'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn Quizze keinerlei Versuche erhalten (Zombie-Quizze). B steigt, wenn viele Versuche nicht abgeschlossen werden (Abbruch). C steigt, wenn Quizze nur von sehr wenigen Lernenden bearbeitet werden (geringe Teilnahme). Versuche werden aus quiz_attempts abgeleitet und auf studierendenähnliche Rollen gefiltert.';
$string['notes_f07'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn verpflichtende Aktivitäten kein klares Fälligkeitsdatum haben. B steigt, wenn bewertete Aktivitäten keine aussagekräftige Beschreibung enthalten. C steigt, wenn zu Kursbeginn eine zentrale Übersicht oder Erwartungsklärung fehlt.';
$string['notes_f08'] = 'Berechnet über die letzten {$a} Tage. A steigt bei hoher formaler Strukturdichte (viele Module pro Abschnitt). B steigt, wenn viele redundante Strukturelemente (Labels, Bücher, Ordner) konkurrieren. C steigt bei stark variierenden Abschnittsgrößen, was die Vorhersagbarkeit der Struktur verringert.';
$string['notes_f09'] = 'Berechnet über die letzten {$a} Tage. A steigt bei vielen Ressourcen pro Abschnitt (Ressourcendichte). B steigt, wenn Ressourcen gegenüber Aktivitäten dominieren (Ressourcenanteil). C steigt, wenn viele Ressourcen reine Datei-Ressourcen sind (Redundanz-Proxy).';
$string['notes_f10'] = 'Berechnet über die letzten {$a} Tage. A steigt, wenn viele Aktivitäten Zugriffsbeschränkungen haben. B steigt, wenn diese Beschränkungen ohne sichtbare Erklärung dargestellt werden. C steigt bei Abhängigkeiten von mehreren Bedingungen.';
$string['notes_f11'] = 'Berechnet über die letzten {$a} Tage. A steigt mit zunehmender Kurslänge und -dichte. B steigt bei sehr großen Abschnitten, die intensives Scrollen erzwingen. C steigt, wenn wenige Orientierungselemente (Labels) zur Strukturierung genutzt werden.';
$string['notes_f12'] = 'Berechnet über die letzten {$a} Tage. A steigt bei hoher Dichte von Abgabeterminen. B steigt, wenn mehrere Deadlines auf denselben Tag fallen. C steigt, wenn Abgaben sehr kurzfristig gesetzt werden und wenig Vorbereitungszeit bleibt.';

$string['formula_f01'] = 'score = clamp( round( 100 * (0.5*A + 0.3*B + 0.2*C) ), 0, 100 )';
$string['formula_f02'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = Aktivitätswechsel-Dichte; B = Unterstützungslücke; C = Wiederholungs-/Verzögerungssignal';
$string['formula_f03'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = strukturelle Fragmentierung; B = Abschnittslast-Ungleichgewicht; C = Modultyp-Entropie';
$string['formula_f04'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = verpflichtende Aktivitäten in der Einstiegsphase; B = frühe Arbeitslast (Proxy); C = Inhaltskomplexität der Einstiegsphase';
$string['formula_f05'] = 'score = clamp( round( 100 * (0.5*A + 0.3*B + 0.2*C) ), 0, 100 ); A = Anteil passiver Betrachtender; B = geringe Interaktionstiefe (invertierter Ø substanzielle Aktionen); C = Engagement-Lücke (Betrachtende vs. Engagierte)';
$string['formula_f06'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = Zombie-Anteil (Quizze mit 0 Versuchen im Zeitraum); B = Abbruch-Anteil (1 - abgeschlossen/gesamt im Zeitraum); C = geringe Teilnahme (Quizze mit < {$a} Lernenden im Zeitraum)';
$string['formula_f07'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = verpflichtende Aktivitäten ohne Fälligkeitsdatum; B = bewertete Aktivitäten ohne Beschreibung; C = fehlender zentraler Erwartungsanker';
$string['formula_f08'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = strukturelle Überlagerung; B = redundante Struktursignale; C = strukturelle Inkonsistenz';
$string['formula_f09'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = Ressourcendichte; B = Ressourcenanteil; C = Ressourcen-Redundanz (Proxy)';
$string['formula_f10'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = Aktivitäten mit Zugriffsbeschränkungen; B = Einschränkungen ohne sichtbare Erklärung; C = verkettete Abhängigkeitsbedingungen';
$string['formula_f10_empty'] = 'Keine Zugriffsbeschränkungen erkannt.';
$string['formula_f11'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = Gesamtlänge des Kurses; B = Abschnittsüberlastung; C = fehlende Navigationsanker';
$string['formula_f12'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = Deadline-Dichte; B = Deadline-Clusterung; C = kurzfristige Deadlines';
$string['formula_f12_empty'] = 'Keine Deadlines erkannt.';

$string['input_f01_a'] = 'Parallele verpflichtende Aktivitäten (Ø pro Abschnitt)';
$string['input_f01_b'] = 'Dichte verpflichtender Ressourcen';
$string['input_f01_c'] = 'Durchschnittliche textuelle Komplexität (normalisiert)';
$string['input_f02_a'] = 'Aktivitätswechsel-Dichte (0..1)';
$string['input_f02_b'] = 'Unterstützungslücke (0..1)';
$string['input_f02_c'] = 'Wiederholungs-/Verzögerungssignal (0..1)';
$string['input_f03_sections_nonempty'] = 'Nicht-leere Abschnitte (sichtbar)';
$string['input_f03_modules_total'] = 'Sichtbare Module (gesamt)';
$string['input_f03_types_unique'] = 'Einzigartige Modultypen';
$string['input_f03_a'] = 'Strukturelle Fragmentierung (0..1)';
$string['input_f03_b'] = 'Abschnittslast-Ungleichgewicht (0..1)';
$string['input_f03_c'] = 'Modultyp-Entropie (0..1)';
$string['input_f04_a'] = 'Verpflichtende Aktivitäten (normalisiert)';
$string['input_f04_b'] = 'Früher Arbeitslast-Proxy (normalisiert)';
$string['input_f04_c'] = 'Komplexität der Einstiegsinhalte (normalisiert)';
$string['input_f04_sections'] = 'Berücksichtigte Einstiegsabschnitte';
$string['input_f05_min_views'] = 'Betrachtenden-Schwelle (min. Aufrufe)';
$string['input_f05_viewers'] = 'Betrachtende (>= min. Aufrufe)';
$string['input_f05_passive'] = 'Passive Betrachtende (0 substanzielle Aktionen)';
$string['input_f05_engaged'] = 'Engagierte Nutzer (>=1 substanzielle Aktion)';
$string['input_f05_substantive_total'] = 'Summe substantieller Aktionen';
$string['input_f05_avg_sub'] = 'Ø substanzielle Aktionen pro Betrachter';
$string['input_f05_a'] = 'Anteil passiver Betrachtender (0..1)';
$string['input_f05_b'] = 'Geringe Interaktionstiefe (0..1)';
$string['input_f05_c'] = 'Engagement-Lücke (0..1)';
$string['input_f06_quizzes_total'] = 'Sichtbare Quizze (gesamt)';
$string['input_f06_zombies'] = 'Quizze mit 0 Versuchen (Zeitraum)';
$string['input_f06_attempts_total'] = 'Versuche (Zeitraum, Studierende)';
$string['input_f06_attempts_finished'] = 'Abgeschlossene Versuche (Zeitraum)';
$string['input_f06_low_participation'] = 'Quizze mit geringer Teilnahme';
$string['input_f06_a'] = 'Zombie-Anteil (0..1)';
$string['input_f06_b'] = 'Abbruch-Anteil (0..1)';
$string['input_f06_c'] = 'Geringe Teilnahme (0..1)';
$string['input_f07_a'] = 'Verpflichtende Aktivitäten ohne Fälligkeitsdatum (Anteil)';
$string['input_f07_b'] = 'Bewertete Aktivitäten ohne Beschreibung (Anteil)';
$string['input_f07_c'] = 'Fehlender Erwartungsanker (0 oder 1)';
$string['input_f08_sections'] = 'Nicht-leere Abschnitte';
$string['input_f08_modules'] = 'Sichtbare Module';
$string['input_f08_structure_modules'] = 'Struktur-Module';
$string['input_f08_a'] = 'Strukturelle Überlagerung (0..1)';
$string['input_f08_b'] = 'Redundante Struktursignale (0..1)';
$string['input_f08_c'] = 'Strukturelle Inkonsistenz (0..1)';
$string['input_f09_sections'] = 'Nicht-leere Abschnitte';
$string['input_f09_modules_total'] = 'Sichtbare Module (gesamt)';
$string['input_f09_resources_total'] = 'Ressourcen (gesamt)';
$string['input_f09_resources_file'] = 'Datei-Ressourcen (resource)';
$string['input_f09_a'] = 'Ressourcendichte (0..1)';
$string['input_f09_b'] = 'Ressourcenanteil (0..1)';
$string['input_f09_c'] = 'Redundanz-Proxy (0..1)';
$string['input_f10_total'] = 'Sichtbare Aktivitäten';
$string['input_f10_restricted'] = 'Aktivitäten mit Einschränkungen';
$string['input_f10_a'] = 'Einschränkungsanteil (0..1)';
$string['input_f10_b'] = 'Einschränkungen ohne Erklärung (0..1)';
$string['input_f10_c'] = 'Verkettete Abhängigkeiten (0..1)';
$string['input_f11_sections'] = 'Nicht-leere Abschnitte';
$string['input_f11_modules'] = 'Sichtbare Module';
$string['input_f11_avg_section_size'] = 'Ø Module pro Abschnitt';
$string['input_f11_labels'] = 'Label-Module';
$string['input_f11_a'] = 'Kurslänge (0..1)';
$string['input_f11_b'] = 'Abschnittsüberlastung (0..1)';
$string['input_f11_c'] = 'Fehlende Anker (0..1)';
$string['input_f12_deadlines'] = 'Berücksichtigte Deadlines';
$string['input_f12_a'] = 'Deadline-Dichte (0..1)';
$string['input_f12_b'] = 'Deadline-Clusterung (0..1)';
$string['input_f12_c'] = 'Kurzfristigkeitsanteil (0..1)';

$string['privacy:metadata'] = 'Friction Radar speichert nur aggregierte kursbezogene Scores im Cache und keine personenbezogenen Daten.';

$string['ui_score'] = 'Score';
$string['ui_formula'] = 'Formel';
$string['ui_inputs'] = 'Berechnungswerte';
$string['ui_param'] = 'Parameter';
$string['ui_value'] = 'Wert';
$string['ui_notes'] = 'Hinweise';

$string['action_f01'] = 'Ein hoher Wert für Kognitive Überlastung zeigt an, dass Lernende gleichzeitig mit zu vielen verpflichtenden Aktivitäten, dichten Ressourcen oder komplexen Materialien konfrontiert werden. Um diese Reibung zu reduzieren, sollten Inhalte bewusst reduziert und Anforderungen sinnvoll sequenziert werden. Überprüfen Sie zunächst die ersten Kursabschnitte und identifizieren Sie, welche Aktivitäten wirklich essenziell sind. Wenn mehrere Aufgaben denselben Zweck erfüllen, können sie zusammengeführt oder teilweise optional gestaltet werden. Lernende profitieren deutlich mehr von wenigen klar erklärten Aktivitäten als von langen Pflichtlisten. Reduzieren Sie, wo möglich, die textliche Komplexität. Lange Beschreibungen, verschachtelte Anweisungen und abstrakte Formulierungen erhöhen die kognitive Belastung. Gliedern Sie Texte in kurze Absätze, verwenden Sie Überschriften und formulieren Sie Erwartungen klar und einfach. Ergänzen Sie komplexe Inhalte durch Visualisierungen, Beispiele oder kurze Videos. Verteilen Sie Anforderungen zeitlich. Vermeiden Sie es, viele verpflichtende Aufgaben in derselben Woche oder im selben Abschnitt zu platzieren. Ein klarer Aufbau von einfachen zu komplexen Anforderungen hilft Lernenden, Sicherheit aufzubauen, bevor höhere Anforderungen gestellt werden.';

$string['action_f02'] = 'Didaktische Engstellen entstehen, wenn Lernaktivitäten schlecht auf Lernziele abgestimmt sind oder Anweisungen unklar bzw. missverständlich formuliert sind. Lernende bearbeiten Aufgaben dann häufig, ohne deren Sinn zu verstehen, was Frustration und Demotivation begünstigt. Prüfen Sie, ob jede Aktivität klar einem Lernziel zugeordnet ist. Formulieren Sie explizit, warum eine Aufgabe existiert und welchen Lernertrag sie haben soll. Trägt eine Aktivität nicht eindeutig zu einem Lernziel bei, sollte sie überarbeitet oder entfernt werden. Verbessern Sie Aufgabenbeschreibungen, indem Sie Eingaben, erwartete Ergebnisse und Bewertungskriterien klar benennen. Vermeiden Sie implizite Annahmen über Vorwissen. Wo sinnvoll, helfen kurze Beispiele für gelungene Abgaben oder typische Fehler. Achten Sie auf Konsistenz. Einheitliche Formate, Begriffe und Strukturen erleichtern die Orientierung. Wenn Lernende Muster erkennen, können sie sich auf das Lernen konzentrieren statt auf das Entschlüsseln von Aufgaben.';

$string['action_f03'] = 'Navigationschaos entsteht, wenn Lernende Materialien, Aktivitäten oder Orientierungspunkte nur schwer finden. Ursache sind häufig inkonsistente Strukturen oder eine zu tiefe Verschachtelung des Kursaufbaus. Schaffen Sie eine klare und wiederkehrende Struktur. Verwenden Sie ein konsistentes Benennungsschema für Abschnitte und Aktivitäten. Beispielsweise kann jeder Themenblock mit einer Übersicht beginnen, gefolgt von Materialien und anschließend Aktivitäten. Vermeiden Sie unnötige Verschachtelungen. Tiefe Ordnerstrukturen und viele Verlinkungen erhöhen das Risiko, dass Lernende die Orientierung verlieren. Wenn Inhalte gruppiert werden müssen, erklären Sie den Zweck und den nächsten Schritt. Nutzen Sie Beschriftungen und Abschnittsbeschreibungen zur Orientierung. Schon ein kurzer erklärender Satz kann den Navigationsaufwand erheblich reduzieren.';

$string['action_f04'] = 'Ein überambitionierter Einstieg liegt vor, wenn Lernende unmittelbar nach Kursbeginn mit hohen Anforderungen konfrontiert werden. Dies kann überfordern, bevor Routinen oder Sicherheit aufgebaut wurden. Überprüfen Sie die ersten ein bis zwei Kursabschnitte. Zählen Sie, wie viele verpflichtende Aktivitäten und Ressourcen dort verlangt werden. Komplexe Aufgaben, Prüfungen oder umfangreiche Lektüren lassen sich oft sinnvoll in spätere Abschnitte verschieben. Nutzen Sie die Anfangsphase zur Orientierung. Führen Sie Kursziele, Struktur und Erwartungen schrittweise ein. Niedrigschwellige Aktivitäten wie kurze Vorstellungsaufgaben, einfache Quizze oder geführte Rundgänge erleichtern den Einstieg. Frühe Erfolgserlebnisse sind entscheidend. Gestalten Sie erste Aufgaben so, dass sie von den meisten Lernenden erfolgreich bewältigt werden können.';

$string['action_f05'] = 'Passive Anwesenheit beschreibt Situationen, in denen Lernende zur Teilnahme verpflichtet sind, ohne dass diese Teilnahme eine inhaltliche Wirkung entfaltet. Aktivitäten dienen dann eher dem Nachweis von Aktivität als dem Lernen. Prüfen Sie, ob Teilnahmeaktivitäten Reflexion, Austausch oder Wissenskonstruktion fördern. Wenn Forenbeiträge oder Abgaben verpflichtend sind, sollten sie Rückmeldung erhalten oder sichtbar in den Kursverlauf eingebunden sein. Reduzieren Sie künstliche Teilnahmepflichten. Verpflichtende Beiträge ohne Interaktion führen häufig zu oberflächlichen Antworten. Besser sind wenige, klar fokussierte Beiträge mit präzisen Fragestellungen. Ersetzen Sie formale Teilnahme nach Möglichkeit durch authentische Aufgaben. Gemeinsame Dokumente, Peer-Feedback oder optionale Diskussionsimpulse fördern oft echtes Engagement.';

$string['action_f06'] = 'Zombie-Quizze sind Tests, die über lange Zeit unverändert wiederverwendet werden und kaum diagnostischen oder lernförderlichen Wert haben. Lernende bearbeiten sie häufig mechanisch und ohne Reflexion. Überprüfen Sie Quizfragen regelmäßig auf Aktualität und Verständlichkeit. Entfernen Sie veraltete oder missverständliche Fragen und stellen Sie sicher, dass sie zum aktuellen Kursinhalt passen. Nutzen Sie Feedback gezielt. Unmittelbares, erklärendes Feedback macht Quizze zu Lerninstrumenten. Schon kurze Erläuterungen zu richtigen und falschen Antworten können den Lernerfolg deutlich steigern. Variieren Sie die Quizformate. Eine Mischung aus Übungsquizzen, formativen Tests und Selbstüberprüfungen erhöht die Motivation.';

$string['action_f07'] = 'Unklare Erwartungen entstehen, wenn Lernende nicht wissen, was für einen erfolgreichen Abschluss erforderlich ist. Dies betrifft unklare Bewertungskriterien, fehlende Beschreibungen oder implizite Annahmen. Stellen Sie sicher, dass alle bewerteten Aktivitäten klar beschrieben sind. Bewertungskriterien oder Rubriken sollten, wenn möglich, vor der Abgabe sichtbar sein. Machen Sie den Arbeitsaufwand transparent. Geben Sie ungefähre Zeitbedarfe und Abgabeformate an, damit Lernende besser planen können. Betrachten Sie Aufgaben aus der Perspektive der Lernenden. Was für erfahrene Lehrende selbstverständlich ist, ist für Studierende oft nicht klar.';

$string['action_f08'] = 'Strukturelle Desorientierung tritt auf, wenn Lernende kein klares mentales Modell des Kursaufbaus entwickeln können. Häufige Ursachen sind inkonsistente Organisation oder häufige strukturelle Änderungen. Halten Sie die Kursstruktur während des Semesters stabil. Vermeiden Sie es, Aktivitäten zu verschieben oder Abschnitte umzubenennen, nachdem der Kurs begonnen hat. Nutzen Sie wiederkehrende Muster. Beispielsweise kann jeder Abschnitt gleich aufgebaut sein: Übersicht, Materialien, Aktivitäten, Bewertung. Setzen Sie strukturierende Signale ein. Abschnittsbeschreibungen, visuelle Trennungen und konsistente Icons erleichtern die Orientierung.';

$string['action_f09'] = 'Ressourcenüberlastung entsteht durch eine große Menge an Dateien, Links und externen Materialien. Lernende haben dann Schwierigkeiten zu erkennen, was wirklich relevant ist. Überprüfen Sie Ihre Materialien regelmäßig. Entfernen Sie veraltete oder redundante Ressourcen und kennzeichnen Sie optionale Inhalte eindeutig. Setzen Sie auf Qualität statt Quantität. Wenige, gut ausgewählte Materialien sind oft wirksamer als eine vollständige Sammlung. Geben Sie Orientierung. Kurze Hinweise, warum eine Ressource wichtig ist und wann sie genutzt werden soll, helfen bei der Auswahl.';

$string['action_f10'] = 'Versteckte Voraussetzungen liegen vor, wenn Aktivitäten auf Vorwissen, Werkzeuge oder Inhalte aufbauen, die nicht explizit genannt werden. Lernende scheitern dann, ohne die Ursache zu verstehen. Identifizieren Sie die Voraussetzungen jeder Aktivität. Wenn Vorwissen oder der Abschluss vorheriger Aufgaben notwendig ist, sollte dies klar kommuniziert werden. Nutzen Sie bedingte Verfügbarkeit bewusst. Machen Sie Abhängigkeiten sichtbar und erklären Sie ihren Zweck, statt Lernende durch Ausprobieren scheitern zu lassen. Stellen Sie bei Bedarf Auffrischungsmaterial oder Verweise bereit. So unterstützen Sie Lernende mit unterschiedlichen Vorkenntnissen.';

$string['action_f11'] = 'Frustriertes Scrollen entsteht durch lange, unstrukturierte Seiten, auf denen relevante Informationen nur durch intensives Scrollen gefunden werden. Zerteilen Sie lange Inhalte in kleinere Einheiten. Überschriften, Akkordeons oder separate Seiten verbessern die Lesbarkeit deutlich. Platzieren Sie zentrale Informationen am Seitenanfang. Lernende sollten ohne langes Scrollen erkennen können, was als Nächstes zu tun ist. Nutzen Sie visuelle Struktur gezielt. Weißraum, Absätze und klare Gliederung erleichtern die Orientierung.';

$string['action_f12'] = 'Deadline-Panik entsteht, wenn viele Abgabefristen zeitlich dicht beieinanderliegen oder unklar kommuniziert werden. Dies führt zu Stress und erhöht das Risiko verpasster Abgaben. Überprüfen Sie den Kurskalender auf Häufungen von Deadlines. Verteilen Sie Abgaben möglichst gleichmäßig über das Semester. Kommunizieren Sie Fristen klar und frühzeitig. Achten Sie auf konsistente Bezeichnungen und darauf, dass Deadlines sowohl in der Aktivität als auch im Kalender sichtbar sind. Erwägen Sie flexible Regelungen. Kulanzfristen oder mehrere Versuche können unnötigen Stress reduzieren, ohne akademische Standards zu gefährden.';
