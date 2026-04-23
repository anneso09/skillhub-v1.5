import styles from './FiltresSidebar.module.css';

const CATEGORIES = ['Développement web', 'Data & IA', 'Design', 'Marketing', 'DevOps'];
const NIVEAUX    = ['Débutant', 'Intermédiaire', 'Avancé'];

const COUNTS_CAT   = { 'Développement web': 7, 'Data & IA': 4, 'Design': 3, 'Marketing': 3, 'DevOps': 3 };
const COUNTS_LEVEL = { 'Débutant': 8, 'Intermédiaire': 7, 'Avancé': 5 };

function LevelBadge({ niveau }) {
  if (niveau === 'Débutant')      return <span className={styles.levelBadgeDebutant}>{niveau}</span>;
  if (niveau === 'Intermédiaire') return <span className={styles.levelBadgeIntermediaire}>{niveau}</span>;
  return <span className={styles.levelBadgeAvance}>{niveau}</span>;
}

export default function FiltresSidebar({ filtres, onChange, onReset }) {
  const { search, categories, niveaux } = filtres;

  const toggleCat = (cat) => {
    const updated = categories.includes(cat)
      ? categories.filter(c => c !== cat)
      : [...categories, cat];
    onChange({ ...filtres, categories: updated });
  };

  const toggleNiveau = (niveau) => {
    const updated = niveaux.includes(niveau)
      ? niveaux.filter(n => n !== niveau)
      : [...niveaux, niveau];
    onChange({ ...filtres, niveaux: updated });
  };

  const resetCats = () => onChange({ ...filtres, categories: [...CATEGORIES] });

  return (
    <div className={styles.sidebar}>

      {/* Recherche */}
      <div className={styles.block}>
        <div className={styles.blockTitle}>Recherche</div>
        <div className={styles.searchWrapper}>
          <span className={styles.searchIcon}>🔍</span>
          <input
            className={styles.searchInput}
            type="text"
            placeholder="Rechercher une formation..."
            value={search}
            onChange={(e) => onChange({ ...filtres, search: e.target.value })}
          />
        </div>
      </div>

      {/* Catégories */}
      <div className={styles.block}>
        <div className={styles.blockTitleRow}>
          <span className={styles.blockTitle} style={{ margin: 0 }}>Catégorie</span>
          <button className={styles.resetBtn} onClick={resetCats}>Tout cocher</button>
        </div>
        <div className={styles.checkList}>
          {CATEGORIES.map(cat => (
            <label key={cat} className={styles.checkLabel}>
              <input
                type="checkbox"
                checked={categories.includes(cat)}
                onChange={() => toggleCat(cat)}
              />
              <span className={styles.checkText}>{cat}</span>
              <span className={styles.checkCount}>{COUNTS_CAT[cat]}</span>
            </label>
          ))}
        </div>
      </div>

      {/* Niveaux */}
      <div className={styles.block}>
        <div className={styles.blockTitle}>Niveau</div>
        <div className={styles.checkList}>
          {NIVEAUX.map(niveau => (
            <label key={niveau} className={styles.checkLabel}>
              <input
                type="checkbox"
                checked={niveaux.includes(niveau)}
                onChange={() => toggleNiveau(niveau)}
              />
              <LevelBadge niveau={niveau} />
              <span className={styles.checkCount} style={{ marginLeft: 'auto' }}>
                {COUNTS_LEVEL[niveau]}
              </span>
            </label>
          ))}
        </div>
      </div>

      {/* Reset global */}
      <button className={styles.btnResetAll} onClick={onReset}>
        Réinitialiser les filtres
      </button>

    </div>
  );
}