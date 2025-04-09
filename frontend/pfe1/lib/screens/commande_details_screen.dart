// lib/screens/commande_details_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/commande.dart';
import '../providers/commande_provider.dart';

class CommandeDetailsScreen extends StatefulWidget {
  final int commandeId;
  
  CommandeDetailsScreen({required this.commandeId});
  
  @override
  _CommandeDetailsScreenState createState() => _CommandeDetailsScreenState();
}

class _CommandeDetailsScreenState extends State<CommandeDetailsScreen> {
  bool _isLoading = true;
  Commande? _commande;
  String? _error;
  
  @override
  void initState() {
    super.initState();
    _loadCommandeDetails();
  }
  
  Future<void> _loadCommandeDetails() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    
    try {
      final commandeProvider = Provider.of<CommandeProvider>(context, listen: false);
      final commande = await commandeProvider.getCommandeDetails(widget.commandeId);
      
      setState(() {
        _commande = commande;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = "Erreur lors du chargement des détails: $e";
        _isLoading = false;
      });
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.grey[900],
        title: Text('Détails de la commande'),
        centerTitle: true,
        iconTheme: IconThemeData(color: Colors.red),
      ),
      body: _isLoading 
          ? Center(child: CircularProgressIndicator(color: Colors.red))
          : _error != null
              ? _buildErrorView()
              : _commande == null
                  ? _buildNotFoundView()
                  : _buildCommandeDetails(),
    );
  }
  
  Widget _buildErrorView() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.error_outline, color: Colors.red, size: 48),
          SizedBox(height: 16),
          Text(
            'Erreur: $_error',
            style: TextStyle(color: Colors.white),
            textAlign: TextAlign.center,
          ),
          SizedBox(height: 16),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            onPressed: _loadCommandeDetails,
            child: Text('Réessayer'),
          ),
        ],
      ),
    );
  }
  
  Widget _buildNotFoundView() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.search_off, color: Colors.grey, size: 48),
          SizedBox(height: 16),
          Text(
            'Commande non trouvée',
            style: TextStyle(color: Colors.white, fontSize: 20),
          ),
        ],
      ),
    );
  }
  
  Widget _buildCommandeDetails() {
    final commande = _commande!;
    
    return SingleChildScrollView(
      padding: EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Card(
            color: Colors.grey[900],
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
            child: Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Commande #${commande.id}',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      _buildStatusChip(commande.status),
                    ],
                  ),
                  SizedBox(height: 16),
                  Text(
                    'Date de commande: ${_formatDate(commande.createdAt)}',
                    style: TextStyle(color: Colors.grey[400]),
                  ),
                  SizedBox(height: 8),
                  Divider(color: Colors.grey[800]),
                  SizedBox(height: 8),
                  Text(
                    'Détails du produit',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  // lib/screens/commande_details_screen.dart (suite)

                  SizedBox(height: 12),
                  _buildProductDetails(commande),
                  SizedBox(height: 16),
                  Divider(color: Colors.grey[800]),
                  SizedBox(height: 8),
                  _buildTrackingInfo(commande),
                  SizedBox(height: 16),
                  if (commande.status == 'en_cours')
                    _buildTrackingTimeline(commande),
                ],
              ),
            ),
          ),
          SizedBox(height: 16),
          Card(
            color: Colors.grey[900],
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
            child: Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Résumé de la commande',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  SizedBox(height: 16),
                  _buildSummaryRow('Sous-total', '${commande.total.toStringAsFixed(2)} €'),
                  SizedBox(height: 8),
                  _buildSummaryRow('Frais de livraison', '0.00 €'),
                  SizedBox(height: 8),
                  _buildSummaryRow('TVA (20%)', '${(commande.total * 0.2).toStringAsFixed(2)} €'),
                  Padding(
                    padding: EdgeInsets.symmetric(vertical: 12),
                    child: Divider(color: Colors.grey[800]),
                  ),
                  _buildSummaryRow(
                    'Total',
                    '${(commande.total * 1.2).toStringAsFixed(2)} €',
                    isBold: true,
                    valueColor: Colors.red,
                  ),
                ],
              ),
            ),
          ),
          SizedBox(height: 16),
          if (commande.status == 'en_attente')
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                icon: Icon(Icons.cancel),
                label: Text('Annuler la commande'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red,
                  padding: EdgeInsets.symmetric(vertical: 12),
                ),
                onPressed: () {
                  _showCancelDialog(commande.id);
                },
              ),
            ),
        ],
      ),
    );
  }
  
  Widget _buildProductDetails(Commande commande) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Image du schéma (ou placeholder)
        ClipRRect(
          borderRadius: BorderRadius.circular(8),
          child: commande.schema?.moto?.image != null
              ? Image.network(
                  commande.schema!.moto!.image!,
                  width: 80,
                  height: 80,
                  fit: BoxFit.cover,
                  errorBuilder: (context, error, stackTrace) {
                    return Container(
                      width: 80,
                      height: 80,
                      color: Colors.grey[700],
                      child: Icon(
                        Icons.image_not_supported,
                        size: 40,
                        color: Colors.grey[500],
                      ),
                    );
                  },
                )
              : Container(
                  width: 80,
                  height: 80,
                  color: Colors.grey[700],
                  child: Icon(
                    Icons.build,
                    size: 40,
                    color: Colors.grey[500],
                  ),
                ),
        ),
        SizedBox(width: 16),
        // Détails du produit
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                commande.schema?.nom ?? 'Schéma',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
              SizedBox(height: 4),
              Text(
                'Version: ${commande.schema?.version ?? "N/A"}',
                style: TextStyle(color: Colors.grey[400]),
              ),
              SizedBox(height: 8),
              Text(
                'Quantité: ${commande.quantite}',
                style: TextStyle(color: Colors.white),
              ),
              SizedBox(height: 4),
              Text(
                'Prix unitaire: ${commande.schema?.price.toStringAsFixed(2) ?? "0.00"} €',
                style: TextStyle(color: Colors.white),
              ),
            ],
          ),
        ),
      ],
    );
  }
  
  Widget _buildStatusChip(String status) {
    Color bgColor;
    IconData icon;
    String statusText;

    switch (status) {
      case 'en_attente':
        bgColor = Colors.blue;
        icon = Icons.pending;
        statusText = 'En attente';
        break;
      case 'en_cours':
        bgColor = Colors.orange;
        icon = Icons.local_shipping;
        statusText = 'En livraison';
        break;
      case 'livree':
        bgColor = Colors.green;
        icon = Icons.check_circle;
        statusText = 'Livrée';
        break;
      case 'annulee':
        bgColor = Colors.red;
        icon = Icons.cancel;
        statusText = 'Annulée';
        break;
      default:
        bgColor = Colors.purple;
        icon = Icons.help;
        statusText = status;
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
            statusText,
            style: TextStyle(color: bgColor, fontSize: 12),
          ),
        ],
      ),
    );
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
    // Estimation de 5 jours de livraison
    final orderDate = commande.createdAt;
    final estimatedDelivery = orderDate.add(Duration(days: 5));
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(height: 16),
        Text(
          'Suivi de livraison',
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.bold,
          ),
        ),
        SizedBox(height: 16),
        _timelineItem('Commande confirmée', _formatDate(orderDate), true),
        _timelineDivider(true),
        _timelineItem('Préparation en cours', _formatDate(orderDate.add(Duration(days: 1))), true),
        _timelineDivider(true),
        _timelineItem('Expédiée', _formatDate(orderDate.add(Duration(days: 2))), true),
        _timelineDivider(false),
        _timelineItem('En cours de livraison', 'En cours', true),
        _timelineDivider(false),
        _timelineItem('Livrée', 'Prévu le ${_formatDate(estimatedDelivery)}', false),
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
  
  Widget _buildSummaryRow(
    String label,
    String value, {
    bool isBold = false,
    Color? valueColor,
  }) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: TextStyle(
            fontWeight: isBold ? FontWeight.bold : FontWeight.normal,
            fontSize: isBold ? 16 : 14,
            color: Colors.white,
          ),
        ),
        Text(
          value,
          style: TextStyle(
            fontWeight: isBold ? FontWeight.bold : FontWeight.normal,
            fontSize: isBold ? 16 : 14,
            color: valueColor ?? Colors.white,
          ),
        ),
      ],
    );
  }
  
  void _showCancelDialog(int commandeId) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        backgroundColor: Colors.grey[900],
        title: Text('Annuler la commande', style: TextStyle(color: Colors.white)),
        content: Text(
          'Êtes-vous sûr de vouloir annuler cette commande ?',
          style: TextStyle(color: Colors.white),
        ),
        actions: [
          TextButton(
            child: Text('Non', style: TextStyle(color: Colors.grey)),
            onPressed: () => Navigator.pop(context),
          ),
          Consumer<CommandeProvider>(
            builder: (context, provider, child) {
              return ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red,
                ),
                child: Text('Oui, annuler'),
                onPressed: provider.isLoading
                    ? null
                    : () async {
                        final result = await provider.cancelCommande(commandeId);
                        Navigator.pop(context);
                        
                        if (result['success']) {
                          // Rafraîchir les détails de la commande
                          _loadCommandeDetails();
                        }
                        
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
  
  String _formatDate(DateTime date) {
    return "${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year}";
  }
}