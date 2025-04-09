// lib/screens/mes_commandes_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/commande.dart';
import '../providers/commande_provider.dart';
import 'commande_details_screen.dart';

class MesCommandesScreen extends StatefulWidget {
  @override
  _MesCommandesScreenState createState() => _MesCommandesScreenState();
}

class _MesCommandesScreenState extends State<MesCommandesScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  
  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 3, vsync: this);
    
    WidgetsBinding.instance.addPostFrameCallback((_) {
      // Charge les commandes au démarrage
      Provider.of<CommandeProvider>(context, listen: false).loadCommandes();
    });
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: Column(
        children: [
          // En-tête avec onglets
          Container(
            color: Colors.grey[900],
            child: Column(
              children: [
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Mes Commandes',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),
                TabBar(
                  controller: _tabController,
                  indicatorColor: Colors.red,
                  labelColor: Colors.red,
                  unselectedLabelColor: Colors.grey,
                  tabs: [
                    Tab(text: 'Toutes'),
                    Tab(text: 'En cours'),
                    Tab(text: 'Livrées'),
                  ],
                ),
              ],
            ),
          ),
          
          // Contenu des onglets
          Expanded(
            child: Consumer<CommandeProvider>(
              builder: (context, commandeProvider, child) {
                if (commandeProvider.isLoading) {
                  return Center(
                    child: CircularProgressIndicator(color: Colors.red),
                  );
                }

                if (commandeProvider.error != null) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.error_outline, color: Colors.red, size: 48),
                        SizedBox(height: 16),
                        Text(
                          'Erreur: ${commandeProvider.error}',
                          style: TextStyle(color: Colors.white),
                          textAlign: TextAlign.center,
                        ),
                        SizedBox(height: 16),
                        ElevatedButton(
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                          onPressed: () => commandeProvider.loadCommandes(),
                          child: Text('Réessayer'),
                        ),
                      ],
                    ),
                  );
                }

                return TabBarView(
                  controller: _tabController,
                  children: [
                    _buildOrdersList(commandeProvider.commandes),
                    _buildOrdersList(commandeProvider.inProgressCommandes),
                    _buildOrdersList(commandeProvider.deliveredCommandes),
                  ],
                );
              },
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: Colors.red,
        onPressed: () {
          Provider.of<CommandeProvider>(context, listen: false).loadCommandes();
        },
        child: Icon(Icons.refresh),
        tooltip: 'Rafraîchir',
      ),
    );
  }

  Widget _buildOrdersList(List<Commande> commandes) {
    if (commandes.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.receipt_long, size: 80, color: Colors.grey),
            SizedBox(height: 16),
            Text(
              'Aucune commande dans cette catégorie',
              style: TextStyle(color: Colors.grey),
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      padding: EdgeInsets.all(16),
      itemCount: commandes.length,
      itemBuilder: (context, index) {
        final commande = commandes[index];
        return _buildCommandeCard(commande);
      },
    );
  }

  Widget _buildCommandeCard(Commande commande) {
    return Card(
      color: Colors.grey[900],
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(10),
      ),
      margin: EdgeInsets.only(bottom: 16),
      child: ExpansionTile(
        tilePadding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        title: Text(
          'Commande #${commande.id}',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
          ),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SizedBox(height: 4),
            Text(
              'Date: ${_formatDate(commande.createdAt)}',
              style: TextStyle(color: Colors.grey[400]),
            ),
            SizedBox(height: 4),
            Text(
              'Montant: ${commande.total.toStringAsFixed(2)} €',
              style: TextStyle(color: Colors.white),
            ),
            SizedBox(height: 4),
            _buildStatusChip(commande.status),
          ],
        ),
        iconColor: Colors.red,
        collapsedIconColor: Colors.red,
        children: [
          Divider(color: Colors.grey[800]),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Détails de la commande',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Schéma: ${commande.schema?.nom ?? "N/A"}',
                      style: TextStyle(color: Colors.white),
                    ),
                    Text(
                      'Quantité: ${commande.quantite}',
                      style: TextStyle(color: Colors.white),
                    ),
                  ],
                ),
                SizedBox(height: 8),
                Text(
                  'Total: ${commande.total.toStringAsFixed(2)} €',
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                Divider(color: Colors.grey[800]),
                SizedBox(height: 8),
                _buildTrackingInfo(commande),
                SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    if (commande.status != 'annulee')
                      ElevatedButton.icon(
                        icon: Icon(Icons.receipt),
                        label: Text('Détails'),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.grey[800],
                        ),
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => CommandeDetailsScreen(commandeId: commande.id),
                            ),
                          );
                        },
                      ),
                    SizedBox(width: 8),
                    if (commande.status == 'en_attente')
                      ElevatedButton.icon(
                        icon: Icon(Icons.cancel, color: Colors.white),
                        label: Text('Annuler', style: TextStyle(color: Colors.white)),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.red,
                        ),
                        onPressed: () {
                          _showCancelDialog(commande.id);
                        },
                      ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  String _formatDate(DateTime date) {
    return "${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year}";
  }

  Widget _buildStatusChip(String status) {
    Color bgColor;
    IconData icon;

    switch (status) {
      case 'en_attente':
        bgColor = Colors.blue;
        icon = Icons.pending;
        break;
      case 'en_cours':
        bgColor = Colors.orange;
        icon = Icons.local_shipping;
        break;
      case 'livree':
        bgColor = Colors.green;
        icon = Icons.check_circle;
        break;
      case 'annulee':
        bgColor = Colors.red;
        icon = Icons.cancel;
        break;
      default:
        bgColor = Colors.purple;
        icon = Icons.help;
    }

    return Container(
      padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: bgColor.withOpacity(0.2),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: bgColor, width: 1),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 16, color: bgColor),
          SizedBox(width: 4),
          Text(
            _translateStatus(status),
            style: TextStyle(color: bgColor, fontSize: 12),
          ),
        ],
      ),
    );
  }

  String _translateStatus(String status) {
    switch (status) {
      case 'en_attente':
        return 'En attente';
      case 'en_cours':
        return 'En livraison';
      case 'livree':
        return 'Livrée';
      case 'annulee':
        return 'Annulée';
      default:
        return status;
    }
  }

  Widget _buildTrackingInfo(Commande commande) {
    switch (commande.status) {
      case 'en_cours':
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Informations de livraison',
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 8),
            Text(
              'Votre commande est en cours de livraison.',
              style: TextStyle(color: Colors.white),
            ),
            SizedBox(height: 12),
            _buildTrackingTimeline(commande),
          ],
        );
      case 'livree':
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Informations de livraison',
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 8),
            Row(
              children: [
                Icon(Icons.check_circle, color: Colors.green, size: 18),
                SizedBox(width: 8),
                Text(
                  'Livré le: ${_formatDate(commande.updatedAt)}',
                  style: TextStyle(color: Colors.white),
                ),
              ],
            ),
          ],
        );
      case 'annulee':
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Raison d\'annulation',
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 8),
            Text(
              'Commande annulée par le client',
              style: TextStyle(color: Colors.white),
            ),
          ],
        );
      default:
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Statut de la commande',
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 8),
            Text(
              'Votre commande a été reçue et est en attente de traitement.',
              style: TextStyle(color: Colors.white),
            ),
          ],
        );
    }
  }

  Widget _buildTrackingTimeline(Commande commande) {
    // Calculer les étapes en fonction du statut de la commande
    bool commandeConfirmee = true;
    bool preparationEnCours = commande.status != 'en_attente';
    bool expediee = commande.status == 'en_cours' || commande.status == 'livree';
    bool livree = commande.status == 'livree';

    return Column(
      children: [
        _timelineItem('Commande confirmée', _formatDate(commande.createdAt), commandeConfirmee),
        _timelineDivider(commandeConfirmee && preparationEnCours),
        _timelineItem('Préparation en cours', preparationEnCours ? _formatDate(commande.updatedAt) : 'À venir', preparationEnCours),
        _timelineDivider(preparationEnCours && expediee),
        _timelineItem('Expédiée', expediee ? _formatDate(commande.updatedAt) : 'À venir', expediee),
        _timelineDivider(expediee && livree),
        _timelineItem('Livrée', livree ? _formatDate(commande.updatedAt) : 'À venir', livree),
      ],
    );
  }

  Widget _timelineItem(String title, String date, bool completed) {
    return Row(
      children: [
        Container(
          width: 20,
          height: 20,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: completed ? Colors.green : Colors.grey,
          ),
          child: completed
              ? Icon(Icons.check, size: 12, color: Colors.white)
              : null,
        ),
        SizedBox(width: 8),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: TextStyle(
                  color: completed ? Colors.white : Colors.grey,
                  fontWeight: completed ? FontWeight.bold : FontWeight.normal,
                ),
              ),
              Text(
                date,
                style: TextStyle(
                  color: Colors.grey,
                  fontSize: 12,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _timelineDivider(bool completed) {
    return Container(
      margin: EdgeInsets.only(left: 9),
      width: 2,
      height: 30,
      color: completed ? Colors.green : Colors.grey,
    );
  }

  void _showCancelDialog(int commandeId) {
    String cancelReason = 'Je n\'en ai plus besoin';

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        backgroundColor: Colors.grey[900],
        title: Text('Annuler la commande', style: TextStyle(color: Colors.white)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              'Êtes-vous sûr de vouloir annuler cette commande ?',
              style: TextStyle(color: Colors.white),
            ),
            SizedBox(height: 16),
            Text(
              'Veuillez sélectionner une raison :',
              style: TextStyle(color: Colors.grey),
            ),
            SizedBox(height: 8),
            DropdownButtonFormField<String>(
              dropdownColor: Colors.grey[800],
              value: cancelReason,
              items: [
                'Je n\'en ai plus besoin',
                'Délai de livraison trop long',
                'J\'ai trouvé moins cher ailleurs',
                'Erreur de commande',
                'Autre raison'
              ].map((String value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(value, style: TextStyle(color: Colors.white)),
                );
              }).toList(),
              onChanged: (newValue) {
                cancelReason = newValue!;
              },
              style: TextStyle(color: Colors.white),
              decoration: InputDecoration(
                enabledBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.grey),
                  borderRadius: BorderRadius.circular(8),
                ),
                focusedBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: Colors.red),
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            child: Text('Retour', style: TextStyle(color: Colors.grey)),
            onPressed: () => Navigator.pop(context),
          ),
          Consumer<CommandeProvider>(
            builder: (context, provider, child) {
              return ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red,
                ),
                child: Text('Confirmer l\'annulation', style: TextStyle(color: Colors.white)),
                onPressed: provider.isLoading
                    ? null
                    : () async {
                        final result = await provider.cancelCommande(commandeId);
                        Navigator.pop(context);
                        
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text(result['message']),
                            backgroundColor: result['success'] ? Colors.green : Colors.red,
                          ),
                        );
                      },
              );
            },
          ),
        ],
      ),
    );
  }
}