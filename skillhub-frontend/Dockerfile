# ── STAGE 1 : builder ──────────────────────────────────────────
# Node pour installer les dépendances et compiler React
FROM node:20-alpine AS builder

WORKDIR /app

# Même logique qu'avec Composer : on copie package.json en premier
# pour profiter du cache Docker si les dépendances ne changent pas
COPY package.json package-lock.json ./
RUN npm ci

# Copie le reste du code et compile
COPY . .
RUN npm run build

# ── STAGE 2 : runtime ──────────────────────────────────────────
# Nginx sert les fichiers statiques compilés — beaucoup plus léger
# qu'un container Node qui ferait tourner Vite en production
FROM nginx:1.25-alpine AS runtime

# Copie les fichiers compilés par Vite (dossier dist/)
# dans le dossier que Nginx sert par défaut
COPY --from=builder /app/dist /usr/share/nginx/html

# Configuration Nginx pour React Router
# Sans ça, si tu vas directement sur /formations, Nginx renvoie 404
# car il cherche un fichier physique qui n'existe pas
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80