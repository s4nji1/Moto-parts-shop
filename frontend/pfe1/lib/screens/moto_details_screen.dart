import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../models/moto.dart';

class MotoDetailsScreen extends StatefulWidget {
  final Moto moto;

  const MotoDetailsScreen({Key? key, required this.moto}) : super(key: key);

  @override
  _MotoDetailsScreenState createState() => _MotoDetailsScreenState();
}

class _MotoDetailsScreenState extends State<MotoDetailsScreen> {
  @override
  Widget build(BuildContext context) {
    final screenHeight = MediaQuery.of(context).size.height;
    final marque = widget.moto.model?.marque ?? 'Marque inconnue';
    final annee = widget.moto.model?.annee ?? 'Année inconnue';
    
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.grey[900],
        title: Text(
          '$marque $annee',
          style: const TextStyle(color: Colors.white),
        ),
        iconTheme: const IconThemeData(color: Colors.red),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Section image et infos principales
            Hero(
              tag: 'moto_image_${widget.moto.id}',
              child: SizedBox(
                height: screenHeight * 0.3,
                width: double.infinity,
                child: CachedNetworkImage(
                  imageUrl: widget.moto.getImageUrl(),
                  fit: BoxFit.cover,
                  placeholder: (context, url) => const Center(
                    child: CircularProgressIndicator(color: Colors.red),
                  ),
                  errorWidget: (context, url, error) => Container(
                    color: Colors.grey[800],
                    child: const Center(
                      child: Icon(
                        Icons.image_not_supported,
                        color: Colors.red,
                        size: 80,
                      ),
                    ),
                  ),
                ),
              ),
            ),

            // Informations détaillées
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Titre et informations
                  Text(
                    marque,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 8),
                  
                  // Caractéristiques
                  Card(
                    color: Colors.grey[850],
                    margin: EdgeInsets.zero,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'Caractéristiques',
                            style: TextStyle(
                              color: Colors.red,
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 16),
                          _buildInfoRow('Marque', marque),
                          const Divider(color: Colors.grey),
                          _buildInfoRow('Année', annee.toString()),
                          const Divider(color: Colors.grey),
                          _buildInfoRow('ID', widget.moto.id.toString()),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              color: Colors.grey[400],
              fontSize: 16,
            ),
          ),
          Text(
            value,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }
}