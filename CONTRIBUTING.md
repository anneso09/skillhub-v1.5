# Contributing — SkillHub V1.5

## Rôles
| Rôle | Responsable | Livrables pilotés |
|------|-------------|-------------------|
| Cloud Architect | Anne-Sophie | Rapport d'audit, schémas C4 |
| DevOps Engineer | Anne-Sophie | Dockerfiles, docker-compose.yml, CI/CD |
| Tech Lead | Anne-Sophie | CONTRIBUTING.md, README.md, sécurité |

## Stratégie de branches
| Branche | Rôle |
|---------|------|
| `main` | Production — code stable, aucun commit direct |
| `dev` | Intégration — branche de travail par défaut |
| `feature/<nom>` | Une branche par fonctionnalité |
| `hotfix/<nom>` | Correctifs urgents |

## Format des commits — Conventional Commits
- `feat:` nouvelle fonctionnalité
- `fix:` correction de bug
- `docker:` fichiers de conteneurisation
- `ci:` pipeline CI/CD
- `docs:` documentation
- `chore:` maintenance

## Procédure de Pull Request
1. Créer une branche `feature/<nom>` depuis `dev`
2. Commiter avec Conventional Commits
3. Ouvrir une PR vers `dev`
4. Vérifier que le pipeline CI passe
5. Merger et supprimer la branche

## Résolution de conflits
En cas de conflit, toujours baser sur la version de `dev`.